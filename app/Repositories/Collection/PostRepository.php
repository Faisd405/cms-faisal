<?php

namespace App\Repositories\Collection;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Collection\CollectionPost;
use App\Models\Collection\CollectionPostContent;

class PostRepository extends BaseRepository implements BaseRepositoryInterface
{
    protected $contentModel;

    public function __construct()
    {
        parent::__construct(new CollectionPost());

        $this->contentModel = new CollectionPostContent();
    }

    public function updateContent($postId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $this->contentModel->updateOrCreate(
                [
                    'post_id' => $postId,
                    'content_type_field_id' => $value['content_type_field_id'],
                    'localization_id' => $content['localeLanguage'],
                ],
                [
                    'value' => $value['value'],
                ]
            );
        }

        $this->contentModel->where('post_id', $postId)
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
        return $this->contentModel->where('post_id', $postId)->where('content_type_field_id', $fieldId)->first();
    }

    public function getAllBySectionSlug($slug, $params = [])
    {
        return $this->model->whereHas('section', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->get();
    }

    public function findBySlug($slug, $params = [])
    {
        $query = $this->prepareQuery($params);

        if (isset($params['filter']['localization_id'])) {
            $query = $query->WhereContentLocalization($params['filter']['localization_id']);
            unset($params['filter']['localization_id']);
        }

        return $query->where('slug', $slug)->first();
    }

    public function find(int $id, array $params = [])
    {
        $query = $this->prepareQuery($params);

        if (isset($params['filter']['localization_id'])) {
            $query = $query->WhereContentLocalization($params['filter']['localization_id']);
            unset($params['filter']['localization_id']);
        }

        return $query->find($id);
    }
}
