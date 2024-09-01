<?php

namespace App\Repositories\Page;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
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
        return $this->prepareQuery($params)->where('slug', $slug)->first();
    }
}
