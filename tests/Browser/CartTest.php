<?php

namespace Rapidez\Core\Tests\Browser;

use Laravel\Dusk\Browser;
use Rapidez\Core\Tests\DuskTestCase;

class CartTest extends DuskTestCase
{
    public function testAddSimpleProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->testProduct->url)
                    ->waitUntilAllAjaxCallsAreFinished()
                    ->click('@add-to-cart')
                    ->waitFor('@minicart-qty')
                    ->waitUntilAllAjaxCallsAreFinished()
                    ->visit('/cart')
                    ->waitFor('@cart-item-name')
                    ->assertSee($this->testProduct->name);
        });
    }

    public function testChangeProductQty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cart')
                    ->waitUntilAllAjaxCallsAreFinished()
                    ->type('@qty-0', 5)
                    ->click('@item-update-0')
                    ->waitUntilAllAjaxCallsAreFinished()
                    ->assertSee($this->testProduct->price * 5);
        });
    }

    public function testRemoveProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cart')
                    ->waitUntilAllAjaxCallsAreFinished()
                    ->click('@item-delete-0')
                    ->waitUntilMissing('@item-delete-0')
                    ->assertDontSee('@cart-item-name');
        });
    }
}
