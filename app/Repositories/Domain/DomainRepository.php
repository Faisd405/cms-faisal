<?php

namespace App\Repositories\Domain;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DomainRepository extends BaseRepository implements BaseRepositoryInterface
{
    use CreateTable;

    public function __construct()
    {
        parent::__construct(new Domain);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $createdData = $this->model->create($data);

            if (isset($data['users'])) {
                $createdData->users()->attach(Auth::id(), ['role' => 'owner']);
            }

            $this->createTableContentType($createdData);
            $this->createTableLanguage($createdData);
            $this->createTablePage($createdData);
            $this->createTableCollection($createdData);
            $this->createTableTagContent($createdData);
            $this->createTableSeoMeta($createdData);

            DB::commit();

            return $createdData;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();
            $domain = $this->model->find($id);

            if ($domain) {
                $domain->users()->detach();
                $domain->delete();

                $this->deleteTable($domain);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
