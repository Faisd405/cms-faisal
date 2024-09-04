<?php

namespace App\Repositories\Localization;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Datamaster\Language;

class LanguageRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Language);
    }

    public function create(array $data)
    {
        $createdData = $this->model->create($data);

        if ($createdData->is_default) {
            $this->changeDefaultLanguage($createdData->id);
        }
    }

    public function update(int $id, array $data)
    {
        $model = $this->model->find($id);

        if ($model) {
            $model->update($data);

            if ($data['is_default']) {
                $this->changeDefaultLanguage($model->id);
            }

            return $model;
        }

        return null;
    }

    public function changeDefaultLanguage($languageId)
    {
        $this->model->where('is_default', true)->update(['is_default' => false]);
        $this->model->where('id', $languageId)->update(['is_default' => true]);
    }
}
