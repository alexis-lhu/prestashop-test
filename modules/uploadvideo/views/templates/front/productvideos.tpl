{if isset($product_videos) && $product_videos|@count > 0}
    <br>
    <div class="product-videos">
        <h4>Product Videos</h4>
        <ul>
            {foreach from=$product_videos item=video}
                <li>
                    <video style="border-radius: 50%; cursor: pointer;" width="70" height="70" 
                           src="{$video.video_path}" content="video/mp4" class="expandable-video" 
                           onclick="expandVideo(this.src)">
                        Your browser does not support the video tag.
                    </video>
                </li>
            {/foreach}
        </ul>
    </div>

    <!-- Lightbox pour l'agrandissement des vidéos -->
    <div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;background-color: rgba(0, 0, 0, 0.9); justify-content: center;align-items: center; z-index: 9999;">
        <span id="close" style="position: absolute; top: 20px; right: 30px; font-size: 30px; color: white;cursor: pointer;" onclick="closeLightbox()">&times;</span>
        <video id="lightbox-video" style="max-width: 90%; max-height: 90%;" controls></video>
    </div>

    <!-- Script JS pour gérer l'agrandissement et la fermeture -->
    <script>
        function expandVideo(videoSrc) {
            var lightbox = document.getElementById("lightbox");
            var lightboxVideo = document.getElementById("lightbox-video");
            lightboxVideo.src = videoSrc; // Définit la source de la vidéo
            lightbox.style.display = "flex"; // Affiche le lightbox
        }

        function closeLightbox() {
            var lightbox = document.getElementById("lightbox");
            var lightboxVideo = document.getElementById("lightbox-video");
            lightboxVideo.pause();  // Arrête la vidéo quand on ferme la lightbox
            lightboxVideo.src = ""; // Réinitialise la source pour éviter le démarrage automatique
            lightbox.style.display = "none"; // Cache le lightbox
        }

        // Fermer la lightbox quand on clique en dehors de la vidéo
        document.getElementById("lightbox").addEventListener("click", function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
    </script>
{else}
    <p>No videos available for this product.</p>
{/if}
