                <footer class="main-footer">
                    <strong>Copyright &copy; 2023-<?= date('Y') ?> <a href="https://eurocaregroup.com/" target="_blank">EUROCARE VENEZUELA, C.A.</a>.</strong>
                    Todos los derechos reservados.
                    <div class="float-right d-none d-sm-inline-block">
                        <b>Version</b> <?= SITE_VERSION ?>
                    </div>
                </footer>

                <!-- Control Sidebar -->
                <aside class="control-sidebar control-sidebar-dark" style="top: 57px; height: 674px; display: block;">
                    <div class="os-resize-observer-host observed">
                        <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
                    </div>
                    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                        <div class="os-resize-observer"></div>
                    </div>
                    <div class="os-content-glue" style="margin: -16px; width: 0px; height: 0px;"></div>
                    <div class="os-padding">
                        <div class="os-viewport os-viewport-native-scrollbars-invisible">
                            <div class="os-content" style="padding: 16px; height: 100%; width: 100%;">
                                <h5>Personalizar SisAdMed</h5>
                                <hr class="mb-2">
                                <div class="mb-4"><input type="checkbox" value="1" class="mr-1"><span>Modo Oscuro</span></div>
                                <h6>Opciones Generales</h6>
                                <div class="mb-4">
                                    <label for="tot_rows">Número de filas a mostrar en tablas</label>
                                    <input type="number" name="tot_rows" id="tot_rows" class="form-control text-xs" placeholder="Número de filas a mostrar en tablas" value="<?= $_SESSION['tot_rows'] ?? 5 ?>">
                                </div>

                            </div>
                        </div>
                    </div>

                </aside>
                <!-- /.control-sidebar -->
                <div id="sidebar-overlay"></div>
                </div>
                <!-- ./wrapper -->

                <!-- jQuery -->
                <script src="<?= JS ?>/jquery.min.js"></script>
                <!-- JQVMap -->
                <script src="<?= JS ?>/jquery.vmap.min.js"></script>
                <script src="<?= JS ?>/jquery.vmap.usa.js"></script>
                <!-- jQuery Knob Chart -->
                <script src="<?= JS ?>/jquery.knob.min.js"></script>
                <!-- jQuery UI 1.11.4 -->
                <script src="<?= JS ?>/jquery-ui.min.js"></script>
                <script src="<?= JS ?>/jquery.validate.min.js"></script>
                <script src="<?= JS ?>/messages_es.js"></script>
                <script src="<?= JS ?>/additional-methods.min.js"></script> <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
                <script>
                    $.widget.bridge('uibutton', $.ui.button)
                </script>
                <!-- Bootstrap 4 -->
                <script src="<?= JS ?>/bootstrap.bundle.min.js"></script>
                <!-- ChartJS -->
                <script src="<?= JS ?>/Chart.min.js"></script>
                <!-- Sparkline -->
                <script src="<?= JS ?>/sparkline.js"></script>
                <!-- daterangepicker -->
                <script src="<?= JS ?>/moment.min.js"></script>
                <script src="<?= JS ?>/daterangepicker.js"></script>
                <!-- Tempusdominus Bootstrap 4 -->
                <script src="<?= JS ?>/tempusdominus-bootstrap-4.min.js"></script>
                <!-- Summernote -->
                <script src="<?= JS ?>/summernote-bs4.min.js"></script>
                <!-- overlayScrollbars -->
                <script src="<?= JS ?>/jquery.overlayScrollbars.min.js"></script>
                <!-- AdminLTE App -->
                <script src="<?= JS ?>/adminlte.js"></script>
                <!-- Select 2 -->
                <script src="<?= JS ?>/select2.full.min.js"></script>
                <script src="<?= JS ?>/select2.es.js"></script>
                <!-- Sweetalert2 -->
                <script src="<?= JS ?>/sweetalert.min.js"></script>
                <!-- Plugins -->
                <script src="<?= PLUGINS ?>/noty/noty.min.js"></script>
                <!-- DataTable-->
                <script src="<?= JS ?>/jquery.dataTables.min.js"></script>
                <script src="<?= JS ?>/dataTables.bootstrap4.min.js"></script>
                <script src="<?= JS ?>/dataTables.responsive.min.js"></script>
                <script src="<?= JS ?>/responsive.bootstrap4.min.js"></script>
                <script src="<?= JS ?>/dataTables.buttons.min.js"></script>
                <script src="<?= JS ?>/buttons.bootstrap4.min.js"></script>
                <script src="<?= JS ?>/jszip.min.js"></script>
                <script src="<?= JS ?>/pdfmake.min.js"></script>
                <script src="<?= JS ?>/vfs_fonts.js"></script>
                <script src="<?= JS ?>/buttons.html5.min.js"></script>
                <script src="<?= JS ?>/buttons.print.min.js"></script>
                <script src="<?= JS ?>/buttons.colVis.min.js"></script>
                <script src="<?= JS ?>/dataTables.checkboxes.min.js"></script>
                <script src="<?= JS ?>/sum().js"></script>
                <!---<script src="<?= JS ?>/dataTables.min.js"></script>--->
                <!--Bootstrap Toggle-->
                <script src="<?= JS ?>/bootstrap-switch.min.js"></script>
                <!--Imput mask-->
                <script src="<?= JS ?>/jquery.maskedinput.min.js"></script>
                <script src="<?= JS ?>/masking-input.js"></script>
                <!-- XLSX -->
                <script src="<?= JS ?>/xlsx.full.min.js"></script>
                <!-- Bootstrap Color Picker -->
                <script src="<?= JS ?>/bootstrap-colorpicker.min.js"></script>
                <!-- MultiSelect -->
                <script src="<?= JS ?>/bootstrap-multiselect.js"></script>
                <!-- SMTP JavaScript -->
                <!-- <script src="https://smtpjs.com/v3/smtp.js"></script> -->
                <!--Read file excel javascript -->
                <script src="<?= JS ?>/read-excel-file.min.js"></script>
                <!-- Url para JavaScript -->
                <!-- Input mask-->
                <script src="<?= JS ?>/jquery.mask.min.js"></script>
                <!-- AutoNumeric-->
                <script src="<?= JS ?>/autonumeric@4.8.1.js"></script>
                <!-- Table To Excel -->
                <script src="<?= JS ?>/jquery.table2excel.js"></script>
                <script src="<?= JS ?>/tableToExcel.js"></script>
                <!-- Read pdf file -->
                <script src='<?= JS ?>/pdf.min.js'></script>
                <!-- Enumerable-->
                <script src="<?= JS ?>/linq.min.js"></script>
                <!--- Moment and datatime-->
                <script src="<?= JS ?>/moment.min.js"></script>
                <script src="<?= JS ?>/datetime.js"></script>
                <!-- Accounting JS -->
                <script src="<?= JS ?>/accounting.min.js"></script>
                <!-- Full Calendar -->
                <script src="<?= JS ?>/fullcalender/main.js"></script>
                <script src="<?= JS ?>/fullcalender/locales-all.min.js"></script>
                <!-- Summernote -->
                <script src="<?= JS ?>/summernote-bs4.min.js"></script>
                <!-- Plugins -->
                <script>
                    const base_url = '<?= base_url ?>';
                    const time_logout = '<?= SITE_TIME_LOGIN ?>';
                    const base_img = '<?= IMG ?>';
                    const ruta_pdf_groups = '<?= RUTA_PDF_GRO ?>';
                </script>
                <script src="<?= ASSETS ?>/app/js/App.js?v=<?= SITE_VERSION; ?>"></script>
                <script src="<?= ASSETS ?>/app/js/Main.js?v=<?= SITE_VERSION; ?>"></script>
                <script src="<?= ASSETS ?>/app/js/Logout.js?v=<?= SITE_VERSION; ?>"></script>

                <!--  -->
                <?php if (!empty($data['function_js'])) : ?>
                    <script src="<?= ASSETS ?>/app/js/<?= $data['function_js']; ?>"></script>
                <?php endif ?>
                <?php if (!empty($data['function_js_mod'])) : ?>
                    <script src="<?= ASSETS ?>/app/js/<?= $data['function_js_mod']; ?>"></script>
                <?php endif ?>
                </body>

                </html>