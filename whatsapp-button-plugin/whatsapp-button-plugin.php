<?php
/**
 * Plugin Name: Tombol WhatsApp
 * Description: Plugin untuk menambahkan tombol WhatsApp di pojok kanan bawah.
 * Version: 1.4
 * Author: Agus Kristanto
 * Author URI : https://winterdesain.my.id
 */

// Mendaftarkan fungsi pada hook wp_footer
add_action('wp_footer', 'tambahkan_whatsapp_button');
add_action('admin_menu', 'whatsapp_button_menu');
add_action('admin_enqueue_scripts', 'whatsapp_admin_scripts');

function whatsapp_button_menu() {
    add_menu_page('Pengaturan WhatsApp', 'WhatsApp', 'manage_options', 'whatsapp-settings', 'whatsapp_settings_page');
    add_action('admin_init', 'register_whatsapp_settings');
}

function register_whatsapp_settings() {
    register_setting('whatsapp-settings-group', 'nomor_whatsapp');
    register_setting('whatsapp-settings-group', 'logo_url');
    register_setting('whatsapp-settings-group', 'ukuran_logo', 'sanitize_text_field');
    register_setting('whatsapp-settings-group', 'lokasi_button', 'sanitize_text_field');
}

function whatsapp_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('whatsapp-admin-script', plugin_dir_url(__FILE__) . 'admin-script.js', array('jquery'), null, true);
}

function whatsapp_settings_page() {
    ?>
    <div class="wrap">
        <h2>Pengaturan WhatsApp</h2>

        <form method="post" action="options.php">
            <?php settings_fields('whatsapp-settings-group'); ?>
            <?php do_settings_sections('whatsapp-settings-group'); ?>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Nomor WhatsApp</th>
                    <td><input type="text" name="nomor_whatsapp" value="<?php echo esc_attr(get_option('nomor_whatsapp')); ?>" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Logo WhatsApp</th>
                    <td>
                        <input type="text" name="logo_url" id="logo_url" value="<?php echo esc_attr(get_option('logo_url')); ?>" />
                        <button class="button" id="upload_logo_button">Unggah Logo</button>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Ukuran Logo (px)</th>
                    <td><input type="text" name="ukuran_logo" value="<?php echo esc_attr(get_option('ukuran_logo', '20')); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Lokasi Tombol</th>
                    <td>
                        <select name="lokasi_button">
                            <option value="kanan_bawah" <?php selected(get_option('lokasi_button', 'kanan_bawah'), 'kanan_bawah'); ?>>Kanan Bawah</option>
                            <option value="kiri_bawah" <?php selected(get_option('lokasi_button', 'kanan_bawah'), 'kiri_bawah'); ?>>Kiri Bawah</option>
                            <!-- Tambahkan opsi-opsi lain sesuai kebutuhan -->
                        </select>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function tambahkan_whatsapp_button() {
    $nomorWhatsApp = esc_attr(get_option('nomor_whatsapp', ''));
    $logoURL = esc_attr(get_option('logo_url', ''));
    $ukuranLogo = esc_attr(get_option('ukuran_logo', '20'));
    $lokasiButton = esc_attr(get_option('lokasi_button', 'kanan_bawah'));

    ?>
    <style>
        #whatsapp-button {
            position: fixed;
            cursor: pointer;
            display: none;
        }

        #whatsapp-button img {
            width: <?php echo $ukuranLogo; ?>px;
            height: <?php echo $ukuranLogo; ?>px;
            margin-right: 5px;
        }

        <?php if ($lokasiButton === 'kanan_bawah') : ?>
            #whatsapp-button {
                bottom: 20px;
                right: 20px;
            }
        <?php elseif ($lokasiButton === 'kiri_bawah') : ?>
            #whatsapp-button {
                bottom: 20px;
                left: 20px;
            }
        <?php endif; ?>
        <!-- Tambahkan aturan-aturan posisi lain sesuai opsi yang Anda tambahkan -->
    </style>

    <div id="whatsapp-button">
        <?php if ($logoURL) : ?>
            <img src="<?php echo $logoURL; ?>" alt="Logo" />
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var whatsappButton = document.getElementById("whatsapp-button");

            whatsappButton.addEventListener("click", function() {
                window.open("https://api.whatsapp.com/send?phone=" + "<?php echo $nomorWhatsApp; ?>", "_blank");
            });

            setTimeout(function() {
                whatsappButton.style.display = "block";
            }, 3000);
        });
    </script>
    <?php
}
?>
