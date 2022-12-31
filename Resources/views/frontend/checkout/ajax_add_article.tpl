{extends file="parent:frontend/checkout/ajax_add_article.tpl"}

{* <!-- Ersetzt das Originale Cross-Selling --> *}
{block name='checkout_ajax_add_cross_selling'}
        {if $mvAjaxCartCrossSelling|@count > 0}
            <div class="modal--cross-selling">
                <div class="panel has--border is--rounded">

                    {* Cross sellung title *}
                    {block name='checkout_ajax_add_cross_selling_title'}
                        <div class="panel--title is--underline">
                            {s name="AjaxAddHeaderCrossSelling"}{/s}
                        </div>
                    {/block}

                    {* Cross selling panel body *}
                    {block name='checkout_ajax_add_cross_selling_panel'}
                        <div class="panel--body">

                            {* Cross selling product slider *}
                            {block name='checkout_ajax_add_cross_slider'}
                                {* <!-- {if $sCrossBoughtToo|count < 1 && $sCrossSimilarShown} --> *}
                                    {$sCrossSellingArticles = $mvAjaxCartCrossSelling}
                                {* <!--
                                {else}
                                    {$sCrossSellingArticles = $sCrossBoughtToo}
                                {/if}
                                --> *}

                                {include file="frontend/_includes/product_slider.tpl" articles=$sCrossSellingArticles}
                            {/block}
                        </div>
                    {/block}
                </div>
            </div>
        {/if}
    {/block}