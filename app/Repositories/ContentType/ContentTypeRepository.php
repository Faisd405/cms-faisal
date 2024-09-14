<?php

namespace App\Repositories\ContentType;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\ContentType\ContentType;
use App\Repositories\ContentType\Traits\CreateTable;
use Illuminate\Support\Facades\DB;

class ContentTypeRepository extends BaseRepository implements BaseRepositoryInterface
{
    use CreateTable;

    protected array $filterable = ['name', 'type'];

    public function __construct()
    {
        parent::__construct(new ContentType);
    }

    public function find(int $id, array $params = [])
    {
        $params['with'] = ['fields'];

        return parent::find($id, $params);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $createdData = $this->model->create($data);

            $this->createPageTable($createdData);

            DB::commit();

            return $createdData;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete(int $id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return null;
        }

        try {
            DB::beginTransaction();
            $deletedModel = $model->delete();

            $this->dropPageTable($model);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
