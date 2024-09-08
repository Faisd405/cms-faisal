<?php

namespace App\Repositories\Page;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Datamaster\Language;
use App\Models\Page\Page;
use App\Models\Page\PageContent;

class PageRepository extends BaseRepository implements BaseRepositoryInterface
{
    protected $contentModel;

    public function __construct()
    {
        parent::__construct(new Page());

        $this->contentModel = new PageContent();
    }

    public function updateContent($pageId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $this->contentModel->updateOrCreate(
                [
                    'page_id' => $pageId,
                    'content_type_field_id' => $value['content_type_field_id'],
                    'localization_id' => $content['localeLanguage'],
                ],
                [
                    'value' => $value['value'],
                ]
            );
        }

        $this->contentModel->where('page_id', $pageId)
            ->whereNotIn('content_type_field_id', array_column($content['item_content'], 'content_type_field_id'))
            ->delete();

        return true;
    }

    public function getAll(array $params = [], bool $withPaginate = true)
    {
        $model = $this->prepareQuery($params);

        if (isset($params['filter']['localization_id'])) {
            $model = $model->WhereContentLocalization($params['filter']['localization_id']);
            unset($params['filter']['localization_id']);
        }

        return $withPaginate
            ? $model->paginate($params['limit'] ?? 15)
            : $model->get();
    }

    public function deleteContentByFieldId($fieldId)
    {
        return $this->contentModel->where('content_type_field_id', $fieldId)->delete();
    }

    public function getOneContent($pageId, $fieldId)
    {
        return $this->contentModel->where('page_id', $pageId)->where('content_type_field_id', $fieldId)->first();
    }

    public function findBySlug($slug, $params = [])
    {
        $query = $this->prepareQuery($params);

        if (isset($params['frontend_service']) && $params['frontend_service']) {
            $query = $this->selectRelationData($query, $params);
        }

        $query = $query->where('slug', $slug)->first();

        if ($query && isset($params['append']) && in_array('content', $params['append'])) {
            $query->content = $query->getValueAttribute();
        }

        return $query;
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

    private function selectRelationData($query, $params)
    {
        $query = $query->with(['contentValue' => function ($contentValue) use ($params) {
            $contentValue->select('id', 'page_id', 'content_type_field_id', 'value', 'localization_id');

            if (isset($params['filter']['localization_id'])) {
                $contentValue->where('localization_id', $params['filter']['localization_id']);
            }
        }]);

        $query = $query->with(['contentValue.contentTypeField' => function ($contentValue) use ($params) {
            $contentValue->select('id', 'content_type_id', 'name', 'type');
        }]);

        $query = $query->with(['contentType' => function ($fields) {
            $fields->select('id');
        }]);

        $query = $query->with(['contentType.fields' => function ($fields) {
            $fields->select('id', 'content_type_id', 'name', 'type');
        }]);

        return $query;
    }
}
