{if isset($sliders) && $sliders}
    {foreach from=$sliders item=slider}
        {if $slider.videos}
            {assign var="videos" value=$slider.videos|json_decode:true}
            <div class="bs-videoslider-container">
                <h3 class="slider-title">{$slider.title|escape:'html':'UTF-8'}</h3>
                <div class="owl-carousel custom-slider" data-slider-id="{$slider.id_bs_videoslider}">
                    {foreach from=$videos item=video}
                        {if isset($video.link) && $video.link}
                            <div class="item" data-fancybox="gallery-{$slider.id_bs_videoslider}" href="{$video.link|escape:'html':'UTF-8'}">
                                <img src="{$video.image|escape:'html':'UTF-8'}" alt="{$video.title|escape:'html':'UTF-8'}">
                                <div class="play-icon">▶</div>
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
        {/if}
    {/foreach}

    {literal}
    <script type="text/javascript">
        $(document).ready(function () {
            $('.custom-slider').each(function() {
                var $slider = $(this);
                var itemCount = $slider.find('.item').length;
                $slider.owlCarousel({
                    loop: itemCount > 1,
                    margin: 10,
                    nav: true,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 3 },
                        1000: { items: 5 }
                    },
                    rtl: true,
                    navText: ['‹', '›']
                });
            });

            $('[data-fancybox]').fancybox({
                loop: true,
                buttons: ['slideShow', 'fullScreen', 'thumbs', 'close'],
                iframe: { preload: false },
                afterLoad: function(instance, current) {
                    if (current.src.includes('aparat.com/embed')) {
                        current.type = 'html';
                        current.content = '<div style="position:relative;width:100%;height:100%;">' + current.src + '</div>';
                        setTimeout(function() {
                            $(current.content).find('script').each(function() {
                                if (this.src) $.getScript(this.src);
                            });
                        }, 100);
                    } else if (!current.src.match(/\.(mp4|webm|ogg)$/i)) {
                        current.type = 'iframe';
                    }
                }
            });
        });
    </script>
    {/literal}
{/if}