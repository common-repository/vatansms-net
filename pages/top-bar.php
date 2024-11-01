<?php
$kredit = get_option("vatansms_kredit");
$fullname = get_option("vatansms_fullname");
$isLogin = get_option("vatansms_is_login");
?>
<style>


</style>
<div class="top-box d-flex">
	<div class="logo">
		<img src="<?php echo vatansms_get_plugin_url() ?>/assets/logo-new.png" style="width:90px; align-self:center">
	</div>
	<?php if (esc_html($isLogin) == 1) { ?>
	<div class="menu-bar-amin">
		<ul>
			<li><i class="far fa-id-card"></i> <?php echo esc_html($fullname); ?></li>

		</ul>
	</div>
	<div class="profile">
		<span class="badge-kredi"><i class="fas fa-coins"></i> Kalan Kredi: <?php echo esc_html($kredit); ?> sms</span>
		<?php if(esc_html($kredit) <= 100){ ?>
			<a href="https://app.vatansms.net/login"  target="_blank" class="ajib"><i class="fas fa-sms"></i> Kredi Yukle</a>
		<?php } ?>
	</div>
	<div class="profile">
		<i class="fas fa-mail-bulk"></i> Gönderici adı: SMS TEST <a href="admin.php?page=vatansms" class="ajib">Değiştir</a>
	</div>
	<div class="logout">
		<a href="admin.php?page=vatansms" style="color: #e3e3e3;"><i class="fa fa-user-circle"></i> Profil</a>
	</div>
	<?php } else{ ?>
	<div class="logout">
		<a href="admin.php?page=vatansms" style="color: #e3e3e3;"><i class="fa fa-user-circle"></i> Giriş Yap</a>
	</div>
	<?php } ?>
	<div class="logout">
		<a href="https://www.vatansms.net/iletisim" target="_blank" style="color: #e3e3e3;"><i class="fas fa-headset"></i> Yardım Merkezi</a>
	</div>
</div>