<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class QuoteQuickTest
 */
class QuoteQuickTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShowPage()
    {
        $this->resetDB();
        $this->visit('quote/quick')
            ->assertResponseOk()
            ->see('Assembly Calculator');
    }

    /**
     * Testing assembly only with correct input
     */
    public function testPostQuoteAssemblyOnly()
    {
        $this->resetDB();
        $this->withoutMiddleware();
        $this->post('quote/quick', [
            'quantity'              => 10,
            'bom_lines'             => 150,
            'smt'                   => 100,
            'bgas'                  => 2,
            'sides'                 => 2,
            'assembled_turn_time'   => '0'
        ])->assertResponseOk()
        ->seeJson([
            'assembled' => [
                'average'       => 58.47346816,
                'finalPrice'    => "2,868.13",
                'finalPriceRaw' => 2868.13,
                'lotPrice'      => 2868.13,
                'price'         => 490.5,
                'unitPrice'     => "286.81"
            ],
            'total'     => "2,868.13",
            'totalRaw'  => 2868.13,
            'success'   => true
        ]);
    }

    /**
     * Quoting both assembly and boards with correct input
     */
    public function testPostQuoteAssemblyAndBoards()
    {
        $this->resetDB();
        $this->withoutMiddleware();
        $this->post('quote/quick', [
            'assembled_turn_time'   => 0,
            'bgas'                  => 2,
            'bom_lines'             => 150,
            'finish'                => 'finish1',
            'height'                => 5,
            'layers'                => '2_layer_',
            'quantity'              => 10,
            'sides'                 => 2,
            'smt'                   => 100,
            'unassembled_quantity'  => 5,
            'unassembled_turn_time' => 0,
            'width'                 => 5
        ])->assertResponseOk()
            ->seeJson([
                'assembled' => [
                    'average'       => 58.47346816,
                    'finalPrice'    => "2,868.13",
                    'finalPriceRaw' => 2868.13,
                    'lotPrice'      => 2868.13,
                    'price'         => 490.5,
                    'unitPrice'     => "286.81"
                ],
                'unAssembled'   => [
                    'subTotal'      => 650,
                    'finalPriceRaw' => 3250,
                    'finalPrice'    => "3,250.00"
                ],
                'total'     => "6,118.13",
                'totalRaw'  => 6118.13,
                'success'   => true
            ]);
    }

    /**
     * Quantity input missing, bom_lines wrong (text), testing assembly validator
     */
    public function testPostQuoteAssemblyMissingInput()
    {
        $this->resetDB();
        $this->withoutMiddleware();
        $this->post('quote/quick', [
            'bom_lines'             => 'foo',
            'smt'                   => 100,
            'bgas'                  => 2,
            'sides'                 => 2,
            'assembled_turn_time'   => '0'
        ])->assertResponseOk()
            ->seeJson([
                'success'   => false,
                'error'     => 'Assembly input missing',
                'errors'    => [
                    'bom_lines' => [
                        'The bom lines must be an integer.'
                    ],
                    'quantity'  => [
                        'The quantity field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Height input missing, widht input wrong (text), testing boards validator
     */
    public function testPostQuoteAssemblyAndBoardsMissingInput()
    {
        $this->resetDB();
        $this->withoutMiddleware();
        $this->post('quote/quick', [
            'assembled_turn_time'   => 0,
            'bgas'                  => 2,
            'bom_lines'             => 150,
            'finish'                => 'finish1',
            'layers'                => '2_layer_',
            'quantity'              => 10,
            'sides'                 => 2,
            'smt'                   => 100,
            'unassembled_quantity'  => 5,
            'unassembled_turn_time' => 0,
            'width'                 => 'foo'
        ])->assertResponseOk()
            ->seeJson([
                'error'     => 'Boards input missing',
                'errors'    => [
                    'height'    => [
                        'The height field is required.'
                    ],
                    'width'     => [
                        'The width must be a number.'
                    ]
                ],
                'success'   => false
            ]);
    }
}
