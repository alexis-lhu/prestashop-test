<div class="panel">
    <h3>Upload Product Videos</h3>
    <form action="http://example.com/admin/index.php?controller=AdminUploadVideo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_product" value="123">
        <div class="form-group">
            <label>Select video files to upload</label>
            <input type="file" name="product_videos[]" multiple="multiple" accept="video/*">
        </div>
        <button type="submit" class="btn btn-primary">Upload Videos</button>
    </form>
</div>
