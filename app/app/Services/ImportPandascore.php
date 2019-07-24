<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;

class ImportPandascore implements Import
{

    /**
     * @var PandaScoreAPI
     */
    private $pandaScoreAPI;

    private $token;

    public function __construct(PandaScoreAPI $pandaScoreAPI)
    {

        $this->pandaScoreAPI = $pandaScoreAPI;

        $this->token = config('services.pandascore.token');
    }

    public function parse()
    {
        $value = Cache::remember('get_matches', 100, function () {
            $this->pandaScoreAPI->get($this->getUrl());
            return $this->pandaScoreAPI->getResult();
        });

        return $value;
    }

    protected function getUrl()
    {
        // так как пока только один урл кинем токен напримую к нему =)
        return config('imports.urls.base') . config('imports.urls.get_matches_upcoming') . '?token=' . $this->token;
    }
}
