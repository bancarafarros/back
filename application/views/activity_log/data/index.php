<section class="content-header">
    <h1>
        <span class="fa fa-file-text-o"></span>&nbsp; Activity Log	<small>Aplikasi Pendukung Penerimaan Wirausahawan Mudah Pertanian (PWMP)</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="<?php echo site_url() ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> Activity Log</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline" role="grid">
                        <?php if (!empty($this->session->flashdata('msg'))) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-success" id="callout">
                                        <h4>Berhasil</h4>
                                        <p><?php echo $this->session->flashdata('msg'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($this->session->flashdata('msgw'))) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-warning" id="callout">
                                        <h4>Perhatian!</h4>
                                        <p><?php echo $this->session->flashdata('msgw'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <form method="get" class="form-inline form-filter">
                        <div class="row">
                            <div class="col-md-12">
								<div class="form-group">
									<input type="text" class="form-control drpicker" name="periodes" placeholder="Periode" value="<?php echo $tgl['tglawal']; ?> - <?php echo $tgl['tglakhir']; ?>">
								</div>
								
								<?php
								if($nama){
									?>
									<div class="form-group">
										<select id="filter-user" class="form-control chosen" name="id_user[]" multiple="" data-placeholder="Username" style="min-width:200px;">
											<option> </option>

											<?php
											foreach ($nama as $r) { ?>
												<option value="<?php echo $r['id_user']; ?>" <?php if(!empty($get_name)) { if(in_array( $r['id_user'], $get_name)) echo 'selected'; } ?>><?php echo $r['username']; ?></option>
											<?php } ?>

										</select>
									</div>
								<?php } ?>
								
								<button type="submit" class="btn btn-primary btn-flat"><span class="fa fa-search"></span> Tampilkan</button>
								<a href="<?php echo site_url("activity_log/data") ?>" class="btn btn-primary btn-flat"><span class="fa fa-refresh"></span> Reset</a>
                                
                            </div>
                        </div>
						
                        </form>
						
                        <hr style="margin-top:5px;">

						<?php if(!empty($data)) { ?>
						
                        <span class="label label-default">Jumlah Data: <?php echo $jumlah; ?></span>
                        
						<div class="table-responsive" style="margin-top:5px;">
							<table id="example1" class="table table-bordered table-hover table-striped table-dynamic">
								<thead>
								<tr>
									<th class="wrap text-left">No</th>
									<th class="wrap">Username</th>
									<th>Aktivitas</th>
									<th class="wrap">Alamat IP</th>
									<th class="wrap" style="min-width:200px;">Waktu</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$a = 1;
								foreach ($data as $row) {
									?>
									<tr>
										<td class="wrap"><?php echo ($this->uri->segment(4) + $a++); ?></td>
										<td><?php echo $row['username']; ?></td>
										<td><?php echo $row['activity']; ?></td>
										<td><?php echo $row['ip_address']; ?></td>
										<td><?php echo $this->tanggal_indo->konversi($row['activity_time']) . " " . $this->tanggal_indo->jam($row['activity_time']) . " WIB"; ?></td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="5">
											<div class="box-footer">
												<ul class="pagination pagination-sm no-margin pull-right">
													<?php echo $link_paging; ?>
												</ul>
											</div>

										</td>
									</tr>
									<tr>
									</tr>
								</tfoot>
							</table>
                        </div>
						<?php } else { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-info">
                                        <h4>Maaf</h4>
                                        <p>Data kosong atau data tidak ditemukan.</p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>