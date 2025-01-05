<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SecretNoteTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SecretNoteTable Test Case
 */
class SecretNoteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SecretNoteTable
     */
    protected $secretNote;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.SecretNote',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SecretNote') ? [] : ['className' => SecretNoteTable::class];
        $this->SecretNote = $this->getTableLocator()->get('SecretNote', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SecretNote);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SecretNoteTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SecretNoteTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
