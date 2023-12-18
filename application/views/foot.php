<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>2023 &copy; Kasrt APP</p>
        </div>
        <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                by <a href="#">Tim IT - PPDWK</a></p>
        </div>
    </div>
</footer>
</div>
</div>
<script src="<?= base_url('template/') ?>assets/static/js/components/dark.js"></script>
<script src="<?= base_url('template/') ?>assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>


<script src="<?= base_url('template/') ?>assets/compiled/js/app.js"></script>
<script src="<?= base_url('template/') ?>assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
<script src="<?= base_url('template/') ?>assets/static/js/pages/form-element-select.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- Need: Apexcharts -->
<script src="<?= base_url('template/') ?>assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url('template/') ?>assets/static/js/pages/dashboard.js"></script>


<script src="<?= base_url('template/') ?>assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="<?= base_url('template/') ?>assets/static/js/pages/simple-datatables.js"></script>
<script src="<?= base_url('template/') ?>assets/jquery.mask.min.js"></script>
<script src="<?= base_url('template/') ?>assets/extensions/flatpickr/flatpickr.min.js"></script>
<script src="<?= base_url('template/') ?>assets/static/js/pages/date-picker.js"></script>

<script src="<?= base_url('template/') ?>assets/extensions/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url('template/') ?>assets/static/js/my-notif.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // Format mata uang.
        $('.uang').mask('000.000.000.000', {
            reverse: true
        });
    })
</script>

<script>
    function redirectToPage() {
        var selectedOption = document.getElementById("mySelect").value;
        window.location.href = '<?= base_url('bp/discrb/') ?>' + selectedOption;
    }

    function redirectToPage2() {
        var selectedOption = document.getElementById("nisSelect").value;
        window.location.href = '<?= base_url('bp/tanggungan/') ?>' + selectedOption;
    }
</script>
</body>

</html>