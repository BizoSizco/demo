<input type="hidden" name="videos" id="videos-input" value="{if isset($videos)}{$videos|json_encode|escape:'html':'UTF-8'}{else}[]{/if}" />

<div class="panel" id="video-panel">
    <div class="panel-heading">
        <button type="button" class="btn btn-primary" id="add-video">
            <i class="icon-plus"></i> {l s='Add New Video' mod='bs_videoslider'}
        </button>
    </div>
    
    <div class="panel-body">
        <div class="alert alert-info">
            <p><i class="icon-info-circle"></i> {l s='For each video you need to provide:' mod='bs_videoslider'}</p>
            <ul>
                <li>{l s='Video Title - Name or description of the video' mod='bs_videoslider'}</li>
                <li>{l s='Thumbnail URL - Direct link to video preview image (e.g., https://example.com/image.jpg)' mod='bs_videoslider'}</li>
                <li>{l s='Video URL - Link or embed code (e.g., YouTube, Vimeo, or Aparat embed)' mod='bs_videoslider'}</li>
            </ul>
        </div>
        
        <div id="video-list">
            {if isset($videos) && $videos|@count > 0}
                {foreach from=$videos item=video}
                    <div class="video-item panel">
                        <div class="panel-heading">
                            <span class="panel-title">
                                {if isset($video.title)}{$video.title|escape:'html':'UTF-8'}{else}{l s='New Video' mod='bs_videoslider'}{/if}
                            </span>
                            <div class="panel-heading-action">
                                <button type="button" class="btn btn-danger delete-video" data-confirm="{l s='Are you sure you want to delete this video?' mod='bs_videoslider'}">
                                    <i class="icon-trash"></i> {l s='Remove' mod='bs_videoslider'}
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label required">{l s='Video Title' mod='bs_videoslider'}</label>
                                <input type="text" 
                                       class="form-control video-title" 
                                       value="{if isset($video.title)}{$video.title|escape:'html':'UTF-8'}{/if}" 
                                       placeholder="{l s='Enter title for this video' mod='bs_videoslider'}" 
                                       required />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label required">{l s='Thumbnail URL' mod='bs_videoslider'}</label>
                                <div class="input-group">
                                    <input type="url" 
                                           class="form-control video-image" 
                                           value="{if isset($video.image)}{$video.image|escape:'html':'UTF-8'}{/if}" 
                                           placeholder="{l s='https://example.com/thumbnail.jpg' mod='bs_videoslider'}" 
                                           required />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default preview-image" title="{l s='Preview Image' mod='bs_videoslider'}">
                                            <i class="icon-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label required">{l s='Video URL' mod='bs_videoslider'}</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control video-url" 
                                           value="{if isset($video.link)}{$video.link|escape:'html':'UTF-8'}{/if}" 
                                           placeholder="{l s='YouTube, Vimeo, or Aparat embed code' mod='bs_videoslider'}" 
                                           required />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default preview-video" title="{l s='Test Video Link' mod='bs_videoslider'}">
                                            <i class="icon-play"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="preview-container" style="display: none;">
                                <img src="" alt="" class="img-responsive thumbnail-preview" />
                            </div>
                        </div>
                    </div>
                {/foreach}
            {else}
                <div class="alert alert-warning no-videos">
                    {l s='No videos added yet. Click "Add New Video" to start.' mod='bs_videoslider'}
                </div>
            {/if}
        </div>
    </div>
</div>

<div id="video-template" style="display: none;">
    <div class="video-item panel">
        <div class="panel-heading">
            <span class="panel-title">{l s='New Video' mod='bs_videoslider'}</span>
            <div class="panel-heading-action">
                <button type="button" class="btn btn-danger delete-video" data-confirm="{l s='Are you sure you want to delete this video?' mod='bs_videoslider'}">
                    <i class="icon-trash"></i> {l s='Remove' mod='bs_videoslider'}
                </button>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label required">{l s='Video Title' mod='bs_videoslider'}</label>
                <input type="text" 
                       class="form-control video-title" 
                       placeholder="{l s='Enter title for this video' mod='bs_videoslider'}" 
                       required />
            </div>
            
            <div class="form-group">
                <label class="control-label required">{l s='Thumbnail URL' mod='bs_videoslider'}</label>
                <div class="input-group">
                    <input type="url" 
                           class="form-control video-image" 
                           placeholder="{l s='https://example.com/thumbnail.jpg' mod='bs_videoslider'}" 
                           required />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default preview-image" title="{l s='Preview Image' mod='bs_videoslider'}">
                            <i class="icon-eye"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label required">{l s='Video URL' mod='bs_videoslider'}</label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control video-url" 
                           placeholder="{l s='YouTube, Vimeo, or Aparat embed code' mod='bs_videoslider'}" 
                           required />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default preview-video" title="{l s='Test Video Link' mod='bs_videoslider'}">
                            <i class="icon-play"></i>
                        </button>
                    </span>
                </div>
            </div>
            
            <div class="preview-container" style="display: none;">
                <img src="" alt="" class="img-responsive thumbnail-preview" />
            </div>
        </div>
    </div>
</div>

{literal}
<script type="text/javascript">
    $(document).ready(function() {
        function updateVideosInput() {
            var videos = [];
            $("#video-list .video-item").each(function() {
                var title = $(this).find(".video-title").val();
                var image = $(this).find(".video-image").val();
                var link = $(this).find(".video-url").val();
                if (title || image || link) {
                    videos.push({
                        title: title,
                        image: image,
                        link: link
                    });
                }
            });
            $("#videos-input").val(JSON.stringify(videos));
            console.log("Updated videos:", $("#videos-input").val());
        }

        $("#add-video").click(function(e) {
            e.preventDefault();
            var template = $("#video-template").html();
            $("#video-list").append(template);
            $(".no-videos").hide();
            updateVideosInput();
        });

        $(document).on("click", ".delete-video", function(e) {
            e.preventDefault();
            var $item = $(this).closest(".video-item");
            if (confirm($(this).data("confirm"))) {
                if ($("#video-list .video-item").length > 1) {
                    $item.fadeOut(300, function() {
                        $(this).remove();
                        updateVideosInput();
                    });
                } else {
                    $item.find("input").val("");
                    updateVideosInput();
                }
            }
        });

        $(document).on("input", ".video-title, .video-image, .video-url", function() {
            updateVideosInput();
        });

        $(document).on("click", ".preview-image", function() {
            var $container = $(this).closest(".video-item").find(".preview-container");
            var url = $(this).closest(".form-group").find(".video-image").val();
            if (url) {
                $container.find(".thumbnail-preview").attr("src", url);
                $container.show();
            }
        });

        $(document).on("click", ".preview-video", function() {
            var url = $(this).closest(".form-group").find(".video-url").val();
            if (url) {
                window.open(url, "_blank");
            }
        });

        $("#video-list").sortable({
            items: ".video-item",
            handle: ".panel-heading",
            cursor: "move",
            axis: "y",
            opacity: 0.6,
            update: function() {
                updateVideosInput();
            }
        });
    });
</script>
{/literal}