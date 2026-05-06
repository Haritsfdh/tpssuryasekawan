@if (session('success'))
    <div class="flash-toast alert alert-success shadow" id="flash-message">
        <strong><i class="fas fa-check-circle mr-1"></i> Success!</strong><br>
        {{ session('success') }}
        <button type="button" class="close text-white" onclick="hideFlash()">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="flash-toast alert alert-danger shadow" id="flash-message">
        <strong><i class="fas fa-times-circle mr-1"></i> Error!</strong><br>
        {{ session('error') }}
        <button type="button" class="close text-white" onclick="hideFlash()">&times;</button>
    </div>
@endif

<style>
.flash-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 250px;
    max-width: 350px;
    border-radius: 8px;
    padding: 15px 20px;
    font-size: 14px;
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; top: 0px; }
    to   { opacity: 1; top: 20px; }
}
</style>

<script>
function hideFlash() {
    const flash = document.getElementById('flash-message');
    if (flash) flash.style.display = 'none';
}

// Auto hide after 4 seconds
setTimeout(hideFlash, 4000);
</script>
