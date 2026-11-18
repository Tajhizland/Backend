<?php

namespace Tests\Unit;

use App\Models\Dictionary;
use App\Services\Search\SearchTerms;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class SearchTermsTest extends TestCase
{
    private Manager $database;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Manager;
        $this->database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $this->database->bootEloquent();
        $this->database->getConnection()->getSchemaBuilder()->create('dictionaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_word');
            $table->string('mean');
        });
        $this->database->getConnection()->getSchemaBuilder()->create('search_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('active')->default(true);
        });
        Dictionary::insert([
            ['original_word' => 'n', 'mean' => 'د'],
            ['original_word' => ',', 'mean' => 'و'],
            ['original_word' => 'tv', 'mean' => 'تلویزیون'],
            ['original_word' => 'tv', 'mean' => 'نمایشگر'],
        ]);
    }

    protected function tearDown(): void
    {
        $this->database->getConnection()->disconnect();
        Model::unsetConnectionResolver();
        parent::tearDown();
    }

    private function search(string $input, bool $splitWords = true): array
    {
        return SearchTerms::apply(SearchItem::where('active', true), 'name', $input, $splitWords)
            ->orderBy('id')->pluck('name')->all();
    }

    public function test_keyboard_conversion_keeps_original_matches_and_visibility_filters(): void
    {
        SearchItem::insert([
            ['name' => 'مدل دو', 'active' => true],
            ['name' => 'model n,', 'active' => true],
            ['name' => 'دو غیرفعال', 'active' => false],
            ['name' => 'نامرتبط', 'active' => true],
        ]);

        $this->assertSame(['مدل دو', 'model n,'], $this->search('n,'));
        $this->assertSame(['مدل دو', 'model n,'], $this->search('N,'));
        $this->assertSame(['مدل دو'], $this->search('دو'));
    }

    public function test_multiword_search_requires_all_words_and_keeps_all_dictionary_meanings(): void
    {
        SearchItem::insert([
            ['name' => 'تلویزیون دو'], ['name' => 'نمایشگر دو'],
            ['name' => 'tv n,'], ['name' => 'تلویزیون'], ['name' => 'دو'],
        ]);

        $this->assertSame(['تلویزیون دو', 'نمایشگر دو', 'tv n,'], $this->search('  tv   n,  '));
    }

    public function test_phrase_search_converts_the_whole_phrase(): void
    {
        SearchItem::insert([['name' => 'دو دو'], ['name' => 'دو مدل دو']]);

        $this->assertSame(['دو دو'], $this->search('n, n,', false));
    }

    public function test_unmapped_english_text_is_still_searchable(): void
    {
        SearchItem::insert([['name' => 'Samsung A55'], ['name' => 'مدل دو']]);

        $this->assertSame(['Samsung A55'], $this->search('Samsung A55'));
    }
}

class SearchItem extends Model
{
    protected $table = 'search_items';
}
