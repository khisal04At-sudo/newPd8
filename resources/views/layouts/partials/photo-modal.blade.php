<!-- Global Image Viewer Modal -->
<div id="photoModal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); align-items: center; justify-content: center; animation: modalFadeIn 0.3s ease-out;">
    <span onclick="closePhotoModal()" style="position: absolute; top: 30px; left: 30px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer; transition: 0.3s; z-index: 10001;" onmouseover="this.style.color='#bbb'" onmouseout="this.style.color='#f1f1f1'">&times;</span>
    <div style="max-width: 90%; max-height: 90%; position: relative; text-align: center;">
        <img id="modalImage" style="max-width: 100%; max-height: 80vh; border-radius: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 4px solid white;">
        <div id="modalCaption" style="margin-top: 20px; color: white; font-size: 1.5rem; font-weight: 800; font-family: 'Cairo', sans-serif;"></div>
        @auth
        <div id="modalEditContainer" style="margin-top: 25px; display: none;">
            <a href="{{ auth()->user()->user_type === 'organization' ? route('organization.profile.edit') : route('dashboard.profile.edit') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: 700; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">تعديل الصورة</a>
        </div>
        @endauth
    </div>
</div>

<style>
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<script>
    function openPhotoModal(imgSrc, captionText, showEdit = false) {
        var modal = document.getElementById("photoModal");
        var modalImg = document.getElementById("modalImage");
        var caption = document.getElementById("modalCaption");
        var editContainer = document.getElementById("modalEditContainer");
        
        if (!modal || !modalImg) return;

        modal.style.display = "flex";
        modalImg.src = imgSrc;
        caption.innerHTML = captionText;
        
        if (editContainer) {
            editContainer.style.display = showEdit ? "block" : "none";
        }
        
        document.body.style.overflow = 'hidden'; 
    }

    function closePhotoModal() {
        var modal = document.getElementById("photoModal");
        if (modal) modal.style.display = "none";
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closePhotoModal();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePhotoModal();
                }
            });
        }
    });
</script>
