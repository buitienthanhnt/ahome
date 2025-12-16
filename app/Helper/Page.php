<?php

namespace App\Helper;

use App\Models\CategoryInterface;
use App\Models\Paper;
use App\Models\PaperInterface;
use App\Models\PaperTagInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\StringHelper;

/**
 *
 */
trait Page
{
    use Nan;
    use StringHelper;

    public function formatPaperCategoryValues($paper_id, $values)
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = array(PaperInterface::PRIMARY_ALIAS => $paper_id, CategoryInterface::PRIMARY_ALIAS => $value);
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function insertPaperCategory($paper_id, $values = [])
    {
        if ($paper_id && $values && $values = $this->formatPaperCategoryValues($paper_id, $values)) {
            DB::beginTransaction();
            try {
                foreach ($values as $value) {
                    DB::table($this->paperCategoryTable())->updateOrInsert($value);
                }
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    /**
     * delete list category of paper
     */
    public function delete_page_category(Paper $paper)
    {
        $paper->toPaperCategory()->delete();
    }

    public function delete_page_tag($paper)
    {
        $paper->joinTags()->delete();
        return false;
    }

    /**
     * @param Paper $paper
     */
    protected function delete_paper_content($paper)
    {
        $paper->joinContent()->delete();
    }

    /**
     * @param string[] $values
     * @param int $entity_id
     * @param string $type
     * @return bool
     */
    public function insertTags($values, $entity_id, $type)
    {
        if ($values && $entity_id && $type && $formatValues = $this->formatTagValues($values, $entity_id, $type)) {
            DB::beginTransaction();
            try {
                foreach ($formatValues as $value) {
                    DB::table($this->paperTagTable())->updateOrInsert($value);
                }
                DB::commit();
                return true;
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
            }
        }
        return false;
    }

    /**
     * @param string[] $values
     * @param int $entity_id
     * @param string $type
     * @return array|null
     */
    public function formatTagValues($values, $entity_id, $type)
    {
        if ($values && $entity_id && $type) {
            $result = [];
            foreach ($values as $value) {
                $result[] = [
                    PaperTagInterface::ATTR_VALUE => $value,
                    PaperTagInterface::ATTR_ENTITY_ID => $entity_id,
                    PaperTagInterface::ATTR_TYPE => $type,
                    PaperTagInterface::ATTR_BASE_VALUE => strtolower($this->vn_to_str($value))
                ];
            }
            return $result;
        }
        return null;
    }
}
