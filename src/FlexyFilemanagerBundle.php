<?php
namespace flexycms\FlexyFilemanagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlexyFilemanagerBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}