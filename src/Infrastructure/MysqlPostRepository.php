<?php

declare(strict_types=1);

namespace Supermetrics\Infrastructure;

use MeekroDB;
use Supermetrics\Domain\Post;
use Supermetrics\Domain\PostsRepositoryInterface;

/**
 * Class MysqlPostRepository
 * @package Supermetrics\Infrastructure
 */
class MysqlPostRepository implements PostsRepositoryInterface
{
    const TABLE = 'posts';

    private MeekroDB $db;

    /**
     * MysqlPostRepository constructor.
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     */
    public function __construct($host, $user, $pass, $db)
    {
        $this->db = new MeekroDB($host, $user, $pass, $db);
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param Post $post
     */
    public function save(Post $post): void
    {
        $data = [
            'type' => $post->getType(),
            'id' => $post->getId(),
            'from_id' => $post->getFromId(),
            'from_name' => $post->getFromName(),
            'message' => $post->getMessage(),
            'created_time' => $post->getCreatedTime()
        ];
        $this->db->insertUpdate(self::TABLE, $data, "id=%s", $post->getId());
    }


    public function remove(Post $post)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @return array
     */
    public function findReportPerMonth()
    {
        $longest =  $this->db->query("select  year(posts.created_time) as year, month(created_time) months ,avg(CHAR_LENGTH(posts.message)) as avg_length, max(CHAR_LENGTH(posts.message)) as longest_post from posts GROUP BY year(created_time), MONTH(created_time)");
        $user = $this->db->query("select year(posts.created_time) as year, posts.from_id, count(*) as all_user_specific_posts , month(posts.created_time) as months from posts GROUP BY year(created_time), months ,  posts.from_id");
        return ['posts' => $longest, 'users_posts' => $user];
    }

    /**
     * @return false|mixed
     */
    public function findReportWeeks()
    {
        return $this->db->query("select year(posts.created_time) as year, count(distinct created_time) as all_posts , WEEK(posts.created_time) as weeks from posts GROUP BY  year(created_time), MONTH(created_time) , weeks");
    }
}
