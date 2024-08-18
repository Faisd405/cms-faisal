<?php

namespace App\Services\Page;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\Page\PageRepository;
use App\Traits\UseAttachment;

class PageService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    protected $contentFieldRepository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;

        $this->contentFieldRepository = new ContentTypeFieldRepository();
    }

    public function updateContent($pageId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $contentTypeField = $this->contentFieldRepository->find($value['content_type_field_id']);

            if ($contentTypeField->type === 'file') {
                $pageContent = $this->repository->getOneContent($pageId, $value['content_type_field_id']);

                if ($pageContent) {
                    $content['item_content'][$key]['value'] = $this->updateFile($value['value'], $pageContent->value, 'uploads/page');
                } else {
                    $content['item_content'][$key]['value'] = $this->uploadFile($value['value'], 'uploads/page');
                }
            }
        }

        return $this->repository->updateContent($pageId, $content);
    }
}
