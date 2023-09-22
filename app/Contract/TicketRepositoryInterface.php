<?php

namespace App\Contract;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * ticket comment list
     *
     * @param  integer $ticket_id ticket id
     * @param  integer $count item count to return
     * @param  string $col sort column
     * @param  string $dir sort direction
     * @return Illuminate\Database\Eloquent\Collection data list
     */
    public function comments($ticket_id, $count = 0, $col = 'id', $dir = 'asc');

    /**
     * comment details
     *
     * @param integer $id comment id
     * @return Illuminate\Database\Eloquent\Model data
     */
    public function comment($id);

    /**
     * add new comment
     *
     * @param  array $data comment data
     * @return Illuminate\Database\Eloquent\Model data
     */
    public function addComment($data);

    /**
     * update comment
     *
     * @param  array $data comment data
     * @param  integer $id comment id
     * @return Illuminate\Database\Eloquent\Model data
     */
    public function updateComment($data, $id);

    /**
     * remove comment
     *
     * @param  integer $id comment id
     * @return Illuminate\Database\Eloquent\Model data
     */
    public function removeComment($id);

    /**
     * check if comment exist
     *
     * @param  integer $id comment id
     * @return integer
     */
    public function commentExist($id);
}
