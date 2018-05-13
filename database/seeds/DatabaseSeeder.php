<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('database.default') !== 'sqlite'){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        App\User::truncate();
        $this->call(UsersTableSeeder::class);

        App\Article::truncate();
        $this->call(ArticlesTableSeeder::class);

        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach($tags as $slug => $name) {
            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug)
            ]);
        }
        $this->command->info('Seeded: tags table');

        /* 변수 선언 */
        $faker = app(Faker\Generator::class);
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        /* 아티클과 태그 연결 */
        foreach($articles as $article) {
            $article->tags()->sync(
              $faker->randomElements(
                  $tags->pluck('id')->toArray(), rand(1, 3)
              )
            );
        }
        $this->command->info('Seeded: article_tag table');

        App\Attachment::truncate();

        if(!File::isDirectory(attachments_path())){
            File::makeDirectory(attachments_path(), 775, true);
        }
        File::cleanDirectory(attachments_path());

        $this->command->error('Downloading images from lorempixel. It takes time...');
        $articles->each(function($article) use ($faker){
            if($path = $faker->image(attachments_path())){
                $filename = File::basename($path);
                $bytes = File::size($path);
                $mime = File::mimeType($path);
                $this->command->warn("File saved: {$filename}");

                $article->attachments()->save(
                    factory(App\Attachment::class)->make(compact('filename', 'bytes', 'mime'))
                );
                $this->command->info('Seeded: attachments table and files');
            }
        });

        App\Signature::truncate();
        factory(App\Signature::class, 100)->create();

        if(config('database.default') !== 'sqlite'){
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
