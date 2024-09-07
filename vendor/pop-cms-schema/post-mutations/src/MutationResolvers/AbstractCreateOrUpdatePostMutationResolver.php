<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateOrUpdateCustomPostMutationResolver;
use PoPCMSSchema\PostMutations\Constants\PostCRUDHookNames;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
/** @internal */
abstract class AbstractCreateOrUpdatePostMutationResolver extends AbstractCreateOrUpdateCustomPostMutationResolver
{
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
    public final function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI) : void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected final function getPostTypeAPI() : PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }
    public function getCustomPostType() : string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
    protected function triggerValidateCreateOrUpdateHook(FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerValidateCreateOrUpdateHook($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    protected function triggerValidateCreateHook(string $customPostType, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerValidateCreateHook($customPostType, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::VALIDATE_CREATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore, $customPostType);
    }
    /**
     * @param string|int $customPostID
     */
    protected function triggerValidateUpdateHook($customPostID, string $customPostType, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerValidateUpdateHook($customPostID, $customPostType, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::VALIDATE_UPDATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore, $customPostType, $customPostID);
    }
    /**
     * @param array<string,mixed> $customPostData
     * @return array<string,mixed>
     */
    protected function addCreateOrUpdateCustomPostData(array $customPostData, FieldDataAccessorInterface $fieldDataAccessor) : array
    {
        return App::applyFilters(PostCRUDHookNames::GET_CREATE_OR_UPDATE_DATA, parent::addCreateOrUpdateCustomPostData($customPostData, $fieldDataAccessor), $fieldDataAccessor);
    }
    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor) : array
    {
        return App::applyFilters(PostCRUDHookNames::GET_UPDATE_DATA, parent::getUpdateCustomPostData($fieldDataAccessor), $fieldDataAccessor);
    }
    /**
     * @return array<string,mixed>
     */
    protected function getCreateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor) : array
    {
        return App::applyFilters(PostCRUDHookNames::GET_CREATE_DATA, parent::getCreateCustomPostData($fieldDataAccessor), $fieldDataAccessor);
    }
    /**
     * @param string|int $customPostID
     */
    protected function triggerExecuteCreateOrUpdateHook($customPostID, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerExecuteCreateOrUpdateHook($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param string|int $customPostID
     */
    protected function triggerExecuteUpdateHook($customPostID, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerExecuteUpdateHook($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::EXECUTE_UPDATE, $customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param string|int $customPostID
     */
    protected function triggerExecuteCreateHook($customPostID, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::triggerExecuteCreateHook($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(PostCRUDHookNames::EXECUTE_CREATE, $customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
