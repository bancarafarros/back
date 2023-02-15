$(document).ready(function() {
    window.location.hash = "step-1";
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    $("#smartwizard").smartWizard({
        selected: 0, // Initial selected step, 0 = first step
        theme: "dots", // theme for the wizard, related css need to include for other than default theme
        justified: true, // Nav menu justification. true/false
        darkMode: false, // Enable/disable Dark Mode if the theme supports. true/false
        autoAdjustHeight: true, // Automatically adjust content height
        cycleSteps: false, // Allows to cycle the navigation of steps
        backButtonSupport: true, // Enable the back button support
        enableURLhash: true, // Enable selection of the step based on url hash
        transition: {
            animation: "none", // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            speed: "400", // Transion animation speed
            easing: "", // Transition animation easing. Not supported without a jQuery easing plugin
        },
        toolbarSettings: {
            toolbarPosition: "bottom", // none, top, bottom, both
            toolbarButtonPosition: "center", // left, right, center
            showNextButton: false, // show/hide a Next button
            showPreviousButton: false, // show/hide a Previous button
            toolbarExtraButtons: [], // Extra buttons to show on toolbar, array of jQuery input/buttons elements
        },
        anchorSettings: {
            anchorClickable: true, // Enable/Disable anchor navigation
            enableAllAnchors: false, // Activates all anchors clickable all times
            markDoneStep: true, // Add done state on navigation
            markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
            removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
            enableAnchorOnDoneStep: true, // Enable/Disable the done steps navigation
        },
        keyboardSettings: {
            keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
            keyLeft: [37], // Left key code
            keyRight: [39], // Right key code
        },
        lang: {
            // Language variables for button
            next: "Selanjutnya",
            previous: "Sebelumnya",
        },
        disabledSteps: [], // Array Steps disabled
        errorSteps: [], // Highlight step with errors
        hiddenSteps: [], // Hidden steps
    });

    $("#form-registrasi").submit(function(e) {
        e.preventDefault();
        // const pass = $("#akun-kata-sandi").val()
        // const passConfirm = $("#akun-kata-sandi-konfirmasi").val()

        // const isMatch = pass == passConfirm
        // const isFiled = pass != '' || passConfirm != ''

        var id_posisi = $("#id_posisi").val();
        var durasi_magang = $("#durasi_magang").val();
        var tanggal_mulai = $("#tanggal_mulai").val();

        const isFilled = id_posisi != "" && durasi_magang != "" && tanggal_mulai != "";

        if (!isFilled) {
            Swal.fire({
                confirmButtonColor: "#3ab50d",
                icon: "error",
                title: "Peringatan",
                text: "Pastikan semua isian sudah terisi",
            });
        } else {
            Swal.fire({
                title: "Apakah Anda yakin semua data sudah benar?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3ab50d",
                cancelButtonColor: "#d33",
                confirmButtonText: "Saya yakin",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "ajax",
                        method: "post",
                        url: site_url + "/auth/register_proses",
                        data: new FormData($("#form-registrasi")[0]),
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                Swal.fire("Sukses!", response.message, "success").then(function() {
                                    document.location.href = site_url;
                                });
                            }
                        },
                        error: function(xmlresponse) {
                            console.log(xmlresponse);
                        },
                    });
                }
            });
        }
    });

    $(".next").click(function(e) {
        e.preventDefault();
        $("#smartwizard").smartWizard("next");
    });

    $(".prev").click(function(e) {
        e.preventDefault();
        $("#smartwizard").smartWizard("prev");
    });

    $("#periode_magang").change(function() {
        var tanggal = $(this).val();
        // console.log(durasi);
        $.ajax({
            type: "ajax",
            method: "POST",
            url: site_url + "/auth/getperiode",
            data: {
                id_batch: tanggal,
            },
            dataType: "json",
            success: function(response) {
                $("[name = tanggal_mulai]").val(response.tanggal);
                $("[name = durasi_magang]").val(response.durasi);
            },
            error: function(xmlresponse) {
                console.log(xmlresponse);
            },
        });
    });
});