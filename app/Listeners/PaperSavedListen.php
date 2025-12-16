<?php

namespace App\Listeners;

use App\Helper\Page;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\PaperTagInterface;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaperSavedListen
{
    use Page;

    protected $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        \Illuminate\Http\Request $request
    ) {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\PaperSaved $event)
    {
        $paper = $event->paper;

        /**
         * update for paper content.
         */
        $paper->joinContent()->delete();
        $this->insertPaperContent($paper);

        /**
         * update paper category.
         */
        $paper->joinPaperCategory()->sync($this->request->get(PaperInterface::EX_ATTR_CATEGORY));

        /**
         * update for paper tags.
         */
        $paper->joinTags()->delete();
        $this->insertTags($paper->id, $this->request->get(Paper::EX_ATTR_TAGS), Paper::EX_ATTR_TAGS);
    }

    /**
     * @param \App\Models\Paper $paper
     * @return void
     */
    protected function insertPaperContent($paper)
    {
        PaperContent::insert($this->convertRequestData($paper->id));
    }

    /**
     * @param int $paper_id
     * @return array
     */
    protected function convertRequestData(int $paper_id): array
    {
        $datas = $this->request->toArray();
        $returnValues = [];
        $storagePath = getStoragePath();
        foreach ($datas as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $val = null;
            $now = now()->toDateTimeString();
            if (strpos($key, 'images_imagex') !== false) {
                $img_desc = $datas[str_replace('images_imagex_', 'imagex_desc_', $key)] ?: null;
                $returnValues[] = [
                    "type" => PaperContentInterface::TYPE_IMAGE,
                    "key" => $key,
                    "value" => str_starts_with($value, $storagePath) ? str_replace($storagePath, '', $value) : $value,
                    "paper_id" => $paper_id,
                    "depend_value" => $img_desc,
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
                continue;
            }

            switch ($key) {
                case PaperContentInterface::TYPE_PRICE:
                    $val = [
                        "type" => PaperContentInterface::TYPE_PRICE,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_SLIDER:
                    $val = [
                        "type" => PaperContentInterface::TYPE_SLIDER,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_CONTENT:
                    $val = [
                        "type" => PaperContentInterface::TYPE_CONTENT,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_TIMELINE_DEPEND:
                    break;
                case PaperContentInterface::TYPE_TIMELINE:
                    $val = [
                        "type" => PaperContentInterface::TYPE_TIMELINE,
                        "key" => $key,
                        "depend_value" => $datas[PaperContentInterface::TYPE_TIMELINE_DEPEND],
                        "value" => $value,
                        "paper_id" => $paper_id
                    ];
                    break;
                case  PaperContentInterface::TYPE_VIDEO:
                    $val = [
                        "type" => PaperContentInterface::TYPE_VIDEO,
                        "key" => $key,
                        "depend_value" => $datas[PaperContentInterface::TYPE_VIDEO_DEPEND],
                        "value" => $value,
                        "paper_id" => $paper_id
                    ];
                    break;
                default:
            }
            if (empty($val)) {
                continue;
            }
            $val["created_at"] = $now;
            $val["updated_at"] = $now;
            $returnValues[] = $val;
        }
        return $returnValues;
    }

    /**
     * @param int $entity_id
     * @param string[] $values
     * @param string $type
     * @return bool
     */
    public function insertTags($entity_id, $values, $type)
    {
        if ($values && $entity_id && $type && $formatValues = $this->formatTagValues($entity_id, $values, $type)) {
            DB::beginTransaction();
            try {
                DB::table($this->paperTagTable())->insert($formatValues);
                DB::commit();
                return true;
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
        return false;
    }

    /**
     * @param int $entity_id
     * @param string[] $values
     * @param string $type
     * @return array|null
     */
    public function formatTagValues($entity_id, $values, $type)
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
