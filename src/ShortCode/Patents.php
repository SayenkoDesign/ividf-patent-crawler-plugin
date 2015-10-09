<?php
namespace IVIDF\ShortCode;

use IVIDF\Crawler\Crawler;
use IVIDF\Entity\Patents as Entity;

class Patents
{
    protected $twig;
    protected $entity;

    public function __construct(\Twig_Environment $twig, entity $entity)
    {
        $this->twig = $twig;
        $this->entity = $entity;
    }

    public function render()
    {
        $patents = $this->entity->getAll();

        $data = [
            'patents' => $patents
        ];

        echo $this->twig->render('page.html.twig', $data);
    }
}