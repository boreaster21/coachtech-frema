<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class E2ETest extends DuskTestCase
{
    /**
     * 商品検索から購入の流れをテスト
     */
    public function testSearchAndPurchaseProduct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('#search-input', 'Sample Product') // 検索入力
                ->press('#search-button') // 検索ボタン
                ->assertVisible('.product-list') // 検索結果の確認
                ->click('.product-item:first-child') // 最初の商品を選択
                ->assertVisible('.product-detail') // 商品詳細が表示されることを確認
                ->press('#add-to-cart') // カートに追加
                ->press('#go-to-checkout') // チェックアウトページへ移動
                ->type('#payment-info', 'valid-payment-details') // 支払い情報を入力
                ->press('#confirm-purchase') // 購入を確定
                ->assertVisible('#purchase-confirmation'); // 購入確認画面を確認
        });
    }

    /**
     * コメント投稿、編集、削除をテスト
     */
    public function testPostEditAndDeleteComment()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('#comment-input', 'This is a test comment') // コメント入力
                ->press('#post-comment') // コメント投稿
                ->assertSee('This is a test comment') // コメントが表示されることを確認
                ->click('.comment-item:first-child .edit-button') // 編集ボタン
                ->type('#comment-edit-input', 'This is an edited comment') // 編集内容を入力
                ->press('#save-comment') // 保存ボタン
                ->assertSee('This is an edited comment') // 編集確認
                ->click('.comment-item:first-child .delete-button') // 削除ボタン
                ->assertDontSee('This is an edited comment'); // 削除確認
        });
    }
}
