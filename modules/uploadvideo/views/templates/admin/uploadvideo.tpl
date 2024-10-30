<br>
<div class="panel">
<h3>Select a Product Video</h3>
    <form action="{$upload_action}" method="post">
        <input type="hidden" name="id_product" value="{$id_product}">
        <input type="hidden"  name="token" value="{$token}">

        <div class="form-group">
            <label>Choose a video from the list</label>
            <select name="selected_video" class="form-control">
                {if isset($videos) && $videos|@count > 0}
                    {foreach from=$videos item=video}
                        <option value="{$video}">{$video}</option>
                    {/foreach}
                {else}
                    <option value="">No videos available</option>
                {/if}
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Select Video</button>
    </form>    
</div>
