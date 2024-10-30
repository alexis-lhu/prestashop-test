{if isset($product_videos) && $product_videos|@count > 0}
    <div class="product-videos">
        <h4>Product Videos</h4>
        <ul>
            {foreach from=$product_videos item=video}
                <li>
                    <video width="320" height="240" controls>
                        <source src="{$video.video_path}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </li>
            {/foreach}
        </ul>
    </div>
{else}
    <p>No videos available for this product.</p>
{/if}
