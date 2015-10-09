<?php
namespace IVIDF\ShortCode;

use IVIDF\Crawler\Crawler;
use IVIDF\Entity\Patents as Entity;

class Patents
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @param \Twig_Environment $twig
     * @param Entity $entity
     */
    public function __construct(\Twig_Environment $twig, entity $entity)
    {
        $this->twig = $twig;
        $this->entity = $entity;
    }

    /**
     * renders and outputs the patent ontrols and table
     */
    public function render()
    {
        $patents = $this->entity->getAll();

        $data = [
            'patents' => $patents
        ];

        echo $this->twig->render('page.html.twig', $data);
    }
}