<?php

namespace Igorsgm\Redash\Resources;

use Igorsgm\Redash\Resources\Concerns\HasParams;
use Illuminate\Support\Facades\Http;

class Dashboards
{
    use HasParams;

    /**
     * Returns a paginated array of dashboard objects.
     *
     * @return array
     */
    public function all()
    {
        return Http::redash($this->params)->get('/api/dashboards')->json();
    }

    /**
     * Create a new dashboard object.
     *
     * @return array
     */
    public function create(array $data)
    {
        return Http::redash($this->params)->post('/api/dashboards', $data)->json();
    }

    /**
     * Returns an individual dashboard object.
     *
     * @return array
     */
    public function get(string $slug)
    {
        return Http::redash($this->params)->get("/api/dashboards/{$slug}")->json();
    }

    /**
     * Archive this dashboard
     *
     * @return array
     */
    public function delete(string $slug)
    {
        return Http::redash($this->params)->delete("/api/dashboards/{$slug}")->json();
    }

    /**
     * Edit an existing dashboard object.
     *
     * @return array
     */
    public function update(int|string $id, array $data)
    {
        return Http::redash($this->params)->post("/api/dashboards/{$id}", $data)->json();
    }
}
