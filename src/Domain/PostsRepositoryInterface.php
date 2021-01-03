<?php

declare(strict_types=1);

namespace Supermetrics\Domain;

/**
 * Interface PostsRepositoryInterface
 * @package Supermetrics\Domain
 */
interface PostsRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param Post $post
     * @return mixed
     */
    public function save(Post $post);


    /**
     * @param Post $post
     * @return mixed
     */
    public function remove(Post $post);
}