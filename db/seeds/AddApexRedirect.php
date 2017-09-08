<?php


use Phinx\Seed\AbstractSeed;

class AddApexRedirect extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(
            array(
                'pattern' => '^(https?:\/\/)(?!www\.)(.*)$',
                'replacement' => '$1www.$2',
                'weight' => 1000,
                'redirects' => 0,
            )
        );

        $redirects = $this->table('redirects');
        $redirects->truncate();
        $redirects->insert($data)
              ->save();
    }
}
