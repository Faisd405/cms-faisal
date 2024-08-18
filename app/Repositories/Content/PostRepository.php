<?php

namespace App\Repositories\Content;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Content\ContentPost;
use App\Models\Content\ContentPostContent;

class PostRepository extends BaseRepository implements BaseRepositoryInterface
{
    protected $contentModel;

    public function __construct()
    {
        parent::__construct(new ContentPost());

        $this->contentModel = new ContentPostContent();
    }

    public function updateContent($postId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $this->contentModel->updateOrCreate(
                [
                    'content_post_id' => $postId,
                    'content_type_field_id' => $value['content_type_field_id'],
                ],
                [
                    'value' => $value['value'],
                ]
            );
        }

        $this->contentModel->where('content_post_id', $postId)
            ->whereNotIn('content_type_field_id', array_column($content['item_content'], 'content_type_field_id'))
            ->delete();

        return true;
    }

    public function deleteContentByFieldId($fieldId)
    {
        return $this->contentModel->where('content_type_field_id', $fieldId)->delete();
    }

    public function getOneContent($postId, $fieldId)
    {
        return $this->contentModel->where('content_post_id', $postId)->where('content_type_field_id', $fieldId)->first();
    }
}
