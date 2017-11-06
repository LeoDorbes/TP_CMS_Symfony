<?php

namespace ContentBundle\Util;

use ContentBundle\Entity\Article;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

/**
 * Article management
 *
 * [CREATE, UPDATE, GET, DELETE]
 */
class ArticleManager
{
    private $em;
    private $slugify;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->slugify = new Slugify();
    }

    /**
     * Create an article in database
     * @param  String $name
     * @param  String $cover file path
     * @param  String $text
     * @param  Integer $category_id
     * @return void
     */
    public function create(
        $name,
        $cover,
        $text,
        $category_id
    )
    {
        $newArticle = new Article();
        $newArticle->setName($name);
        $newArticle->setCover($cover);
        $newArticle->setText($text);
        $newArticle->setCategoryId($category_id);
        $this->em->persist($newArticle);
        $this->em->flush();
    }

    public function update($article)
    {
        $this->em->flush();
    }

    /**
     * Get an article from database
     * @param  Integer $id
     * @return Article|Article[]
     */
    public function get($id = NULL)
    {
        if (is_null($id)) {
            $res = $this->em->getRepository(Article::class)->findAll();
        } else {
            $res = $this->em->getRepository(Article::class)->findOneById($id);
        }
        var_dump($res);
        //TODO PARSE THIS RESULTSET
        //       Find an article from ID or if no ID find all articles, then return
    }

    /**
     * Get an article and delete it
     * @param  Integer $id
     * @return void
     */
    public function delete($id)
    {
        $article = $this->get($id);

        if (is_null($article))
            return;

        $this->em->remove($article);
        $this->em->flush();
    }
}
