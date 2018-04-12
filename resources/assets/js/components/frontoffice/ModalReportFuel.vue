<template>
	<div id="reportFuel" class="modal bottom-sheet">
		<div id="pdfFuel" class="modal-content">
			<h4>รายงานการเติมน้ำมัน</h4>
			<div class="row">
				<div class="col s12 m12 l12">
					<table class="centered">
						<thead>
						<tr>
							<th>คนขับรถ</th>
							<th>ทะเบียนรถ</th>
							<th>วันที่เติมน้ำมัน</th>
							<th>ผู้เติมน้ำมัน</th>
							<th>ประเภทน้ำมัน</th>
							<th>จำนวน <br>(ลิตร)</th>
							<th>ระยะทางที่วิ่ง <br>(กิโลเมตร)</th>
							<th>อัตราการใช้น้ำมัน <br>(กิโลเมตร/ลิตร)</th>
						</tr>
						</thead>

						<tbody>
							<tr v-for="fuel in fuels">
								<td>{{ fuel.user.firstname }} {{ fuel.user.lastname }}</td>
								<td>{{ fuel.truck.license_plate }}</td>
								<td>{{ fuel.fuel.fuel_date }}</td>
								<td>{{ fuel.fuel.gas_employee }}</td>
								<td>{{ tranGasType(fuel.fuel.gas_type) }}</td>
								<td>{{ fuel.fuel.liter }}</td>
								<td>{{ fuel.fuel.total_distance }}</td>
								<td>{{ fuel.fuel.gas_per_distance }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="modal-content">
			<div class="section materialboxed">
				<div class="row">
					<div class="col s12 m8 l6 offset-l3 offset-m2">
						<canvas id="myChartFuel" width="100%" height="100%"></canvas>								
					</div>
				</div>
			</div>
		</div>

		<div class="modal-content">
			<div class="section left-align">
				<div class="row">
					<div class="input-field col s12 m9 l5 offset-l7 offset-m3">
						<blockquote>
							<p>น้ำมันที่ใช้: {{ totalLiter }} ลิตร</p>
							<p>ระยะทางที่วิ่ง: {{ totalDistance }} กิโลเมตร</p>
							<p>อัตราการใช้น้ำมันเฉลี่ย: {{ gasPerDistance }} กิโลเมตร/ลิตร</p>
						</blockquote>
					</div>
				</div>						
			</div>
		</div>

		<div class="modal-footer">
			<a @click="onClickPrintReportFuel" class="modal-action waves-effect waves-green btn-flat">PRINT</a>
			<a type="button" class="modal-action modal-close waves-effect waves-green btn-flat">ปิด</a>
		</div>
	</div>
</template>

<script>
	import Chart from 'chart.js'
  import printJS from 'print-js';

	export default {
		data() {
			return {
				totalDistance: 0,
				totalLiter: 0,
				totalGasPerDistance: 0,
			}
		},
		props: {
			fuels: { require: true },
		},
		methods: {
			onClickPrintReportFuel: function() {
				let printData = [];
				$.each(this.fuels, function(index, fuel) {
					let gasType = fuel.fuel.gas_type;

					if (gasType == 'gasoline') {
						gasType =  'น้ำมันเครื่องยนต์';
					} else if (gasType == 'lubricator') { 
							gasType =  'น้ำมันห้องเครื่อง';
					} else if (gasType == 'gear_box_oil') { 
							gasType =  'น้ำมันห้องเกียร์';
					} else if (gasType == 'final_gear_oil') { 
							gasType =  'น้ำมันเฟืองท้าย';
					} else {
							gasType =  '';
					}
					
					const dataPush = {
						name: `${fuel.user.firstname} ${fuel.user.lastname}`,
						licensePlate: fuel.truck.license_plate,
						fueDate: fuel.fuel.fuel_date,
						gasEmployee: fuel.fuel.gas_employee,
						gasType,
						liter: fuel.fuel.liter,
						totalDistance: fuel.fuel.total_distance,
						gasPerDistance: fuel.fuel.gas_per_distance,
					}
					printData.push(dataPush);
				}); 
				printJS({
					printable: printData, 
					properties: [
						'name', 
						'licensePlate', 
						'fueDate', 
						'gasEmployee', 
						'gasType',
						'liter', 
						'totalDistance', 
						'gasPerDistance'
					],
					header: 'รายงานการเติมน้ำมัน',
					type: 'json'
				});
			},
			computeGraph:function() {
				let distance = 0;
				let liter = 0;
				let gasPerDistance = 0;
				let count = 0;
				$.each(this.fuels, function(index, fuel) {
					distance = distance + fuel.fuel.total_distance	
					liter = liter + fuel.fuel.liter	
					gasPerDistance = gasPerDistance + fuel.fuel.gas_per_distance
					count = index + 1;	
				}); 
				this.totalDistance = distance;
				this.totalLiter = liter.toFixed(2);
				this.gasPerDistance = (gasPerDistance / count).toFixed(2)
				////////////////////
				let date = [];
				let gasPerDistanceGraph = [];
				let literGraph = [];
				let distanceGraph = [];

				$.each(this.fuels, function(index, fuel) {
					date.push(fuel.fuel.fuel_date);

					gasPerDistanceGraph.push(fuel.fuel.gas_per_distance)
					literGraph.push(fuel.fuel.liter)
					distanceGraph.push(fuel.fuel.total_distance)
				}); 
				this.showGraph(date, gasPerDistanceGraph, literGraph, distanceGraph);
			},
			showGraph: function(date, gasPerDistanceGraph, literGraph, distanceGraph) {
				const ctxFuel = document.getElementById("myChartFuel");
				const myChartFuel = new Chart(ctxFuel, {
					type: 'line',
					data: {
						labels: date,
						datasets: [
							{
								label: 'อัตราการใช้น้ำมัน (กิโลเมตร/ลิตร)',
								data: gasPerDistanceGraph,
								fill: false,
								borderColor: 'rgba(255,99,132,1)',
							},
							{
								label: 'ระยะทางที่วิ่ง (กิโลเมตร)',
								data: distanceGraph,
								fill: false,							
								borderColor: 'rgba(75,192,192,1)',
							},
							{
								label: 'น้ำมันที่เติม (ลิตร)',
								data: literGraph,
								fill: false,							
								borderColor: 'rgba(54, 162, 235, 1)',
							},
						]
					},
				});
			},
			tranGasType: function(gasType) {
				if (gasType == 'gasoline') {
					return 'น้ำมันเครื่องยนต์';
				} else if (gasType == 'lubricator') { 
						return 'น้ำมันห้องเครื่อง';
				} else if (gasType == 'gear_box_oil') { 
						return 'น้ำมันห้องเกียร์';
				} else if (gasType == 'final_gear_oil') { 
						return 'น้ำมันเฟืองท้าย';
				} else {
						return '';
				}
			}
		},
		watch: {
			fuels: function () {
				this.computeGraph();
			}
		},
		mounted() {
			$('.materialboxed').materialbox();
			this.computeGraph();
		},
	}
</script>