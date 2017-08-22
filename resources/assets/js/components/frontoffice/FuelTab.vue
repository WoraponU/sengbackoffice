<template>
	<div class="container center-align">
		<form action="/fuel" method="POST" autocomplete="on">
			<input type="hidden" name="_token" v-model="csrfToken">

			<TruckAndDriverMap v-on:truckIdSelected="truckIdSelected"></TruckAndDriverMap>
			<div class="container">
				<div class="row left-align card-panel">
					<div class="row">
						<div class="col offset-l1">
							<h5>รายละเอียดการเติมน้ำมัน</h5>						
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l4 offset-l2">
							<input type="date" class="datepicker" id="fuelDate" name="fuelDate" required>
							<label for="fuelDate">วันที่ <span class="icon-star">*</span></label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l4 offset-l2">
							<input id="gasEmployee" name="gasEmployee" type="text" class="validate" required>
							<label for="gasEmployee">ผู้เติม <span class="icon-star">*</span></label>
						</div>  
						<div class="col s12 m6 l4">
							<label>ประเภทน้ำมัน <span class="icon-star">*</span></label>
							<select @change="truckIdSelected(truckId)" name="gasType" ref="gasType" class="browser-default" required>
								<option value="" disabled>โปรดระบุ</option>
								<option value="gasoline" selected>น้ำมันเครื่องยนต์</option>
								<option value="lubricator">น้ำมันห้องเครื่อง</option>
								<option value="gear_box_oil">น้ำมันห้องเกียร์</option>
								<option value="final_gear_oil">น้ำมันเฟืองท้าย</option>
							</select>
						</div>   
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l4 offset-l2">
							<input id="lastNumberCar" name="lastNumberCar" type="number" min="1" v-model="lastNumberCar" class="validate" required>
							<label for="lastNumberCar">หมายเลขกิโลเมตรครั้งก่อน <span class="icon-star">*</span></label>
						</div>   
						<div class="input-field col s12 m6 l4">
							<input id="presentNumberCar" name="presentNumberCar" type="number" :min="parseInt(lastNumberCar)+1" v-model="presentNumberCar" class="validate" required>
							<label for="presentNumberCar">หมายเลขกิโลเมตรล่าสุด <span class="icon-star">*</span></label>
						</div>  
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l4 offset-l2">
							<input id="liter" name="liter" type="number" min="1" step="0.01" v-model="liter" class="validate" required>
							<label for="liter">จำนวนลิตร <span class="icon-star">*</span></label>
						</div> 
					</div>
					<div class="row">
						<div class="input-field col s12 m12 l8 offset-l2">
							<textarea id="note" name="note" class="materialize-textarea"></textarea>
							<label for="note">บันทึกรายละเอียด</label>
						</div>
					</div>

					<div class="section">
						<div class="row">
							<div class="input-field col s12 m12 l8 offset-l2">
								<blockquote>
									<p class="flow-text">ระยะทางที่วิ่ง: {{ totalDistance }} กิโลเมตร</p>
									<p class="flow-text">น้ำมันที่ใช้: {{ totalGas }} ลิตร</p>
									<p class="flow-text">อัตราการใช้น้ำมัน: {{ gasPerDistance }} กิโลเมตร/ลิตร</p>
								</blockquote>
								<input type="hidden" name="totalDistance" :value="totalDistance">
								<input type="hidden" name="gasPerDistance" :value="gasPerDistance">
							</div>
						</div>						
					</div>			

					<div class="input-field col s12 m12 l12 right-align">
						<button class="btn waves-effect waves-light" type="submit">เพิ่ม
							<i class="material-icons right">send</i>
						</button>
					</div>
				</div>
			</div>  
		</form>               
	</div>
</div>
</template>

<script>
    import axios from 'axios';

	export default {
		data() {
			return {
				csrfToken: window.Laravel.csrfToken,

				lastNumberCar: 1,
				presentNumberCar: 2,
				liter: 1,
				truckId: '',
			}
		},
		computed: {
			totalDistance: function () {
				return this.presentNumberCar - this.lastNumberCar;
			},
			totalGas: function () {
				return this.liter;
			},
			gasPerDistance: function () {
				return ((this.presentNumberCar - this.lastNumberCar) / this.liter).toFixed(2);
			}
		},
		updated() {
			Materialize.updateTextFields();					
		},
		methods: {
			truckIdSelected: function (truckIdSelected) {
				if (truckIdSelected != '') {
					this.truckId = truckIdSelected
					const gasType = this.$refs.gasType.value	
					
					axios.get(`/fuel/last-number-car/${truckIdSelected}`, {
						params: {
							gasType: gasType						
						}
					})
					.then((response) => {
						if (!(Object.keys(response.data).length === 0 
						&& response.data.constructor === Object)) {
							this.lastNumberCar = response.data
							this.presentNumberCar = parseInt(response.data) + 1
						} else {
							this.lastNumberCar = 1
							this.presentNumberCar = 2
						}
					})
					.catch(function (error) {
						console.log(error);
					});
				}
			}
		}
	}
</script>