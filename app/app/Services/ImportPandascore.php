<?php


namespace App\Services;


use App\League;
use App\Matche;
use App\Services\DTO\DTO;
use App\Services\DTO\MatchDTO;
use App\Team;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Object_;

class ImportPandascore implements Import
{

    /**
     * @var PandaScoreAPI
     */
    private $pandaScoreAPI;

    public function __construct(PandaScoreAPI $pandaScoreAPI)
    {
        $this->pandaScoreAPI = $pandaScoreAPI;
    }

    /**
     * @return bool|void
     * @throws Exception
     */
    public function parse()
    {
        $value = Cache::remember('get_matches', 5, function () {
            $this->pandaScoreAPI->get($this->getUrl());
            return $this->pandaScoreAPI->getResult();
        });

        if ($value) {
            return $this->prepareData($value);
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return config('imports.urls.base') . config('imports.urls.get_matches_upcoming');
    }

    /**
     * Формируем и сохраняем данные в базу
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    protected function prepareData(array $data)
    {
        if (!$data) {
            throw new Exception("Data of is not valid.");
        }

        foreach ($data as $item) {
            $this->save($item);
        }

    }

    /**
     * Записываем в базу
     *
     * @param object $item
     * @throws Exception
     */
    protected function save($item)
    {
        \DB::transaction(function () use ($item) {

            // сформируем массив данных лиги
            if (!empty($item->league)) {

                $league = $item->league;

                $league_res = League::updateOrCreate([
                    'id' => $league->id,
                ], [
                    'image_url'      => $league->image_url,
                    'name'           => $league->name,
                    'slug'           => $league->slug,
                    'url'            => $league->url,
                    'live_supported' => $league->live_supported,
                    'modified_at'    => (new DateTime($league->modified_at))->format("Y-m-d H:i:s"),
                ]);
            }

            // сформируем массив данных по матчу
            $match[] = Matche::updateOrCreate([
                'id'        => $item->id,
                'league_id' => $item->league_id,
            ], [
                'name'            => $item->name,
                'slug'            => $item->slug,
                'status'          => $item->status,
                'match_type'      => $item->match_type,
                'number_of_games' => $item->number_of_games,
                'draw'            => $item->draw,
                'end_at'          => $item->end_at,
                'forfeit'         => $item->forfeit,
                'winner'          => $item->winner,
                'winner_id'       => $item->winner_id,
                'modified_at'     => (new DateTime($item->modified_at))->format("Y-m-d H:i:s"),
                'begin_at'        => (new DateTime($item->begin_at))->format("Y-m-d H:i:s"),
            ]);

            // сформируем массив данных по участникам (команды)
            $opponent_id = [];

            foreach ($item->opponents as $opponent) {

                $opponent = $opponent->opponent;

                $opponent_id[] = $opponent->id;

                $teame = Team::updateOrCreate([
                    'id' => $opponent->id,
                ], [
                    'acronym'   => $opponent->acronym,
                    'image_url' => $opponent->image_url,
                    'name'      => $opponent->name,
                    'slug'      => $opponent->slug,
                ]);
            }

            // участников к матчу
            $match->teams()->sync($opponent_id);

        });
    }
}
