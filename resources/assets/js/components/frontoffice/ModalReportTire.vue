<template>
    <div id="reportTire" class="modal bottom-sheet">
		<div id="pdfTire" class="modal-content">
			<h4>รายงานการเปลี่ยนยาง</h4>
            <div class="row">
                <div class="col s12 m12 l12">
                    <table class="centered">
                        <thead>
                        <tr>
                            <th>คนขับรถ</th>
                            <th>ทะเบียนรถ</th>
                            <th>วันที่เปลี่ยนยาง</th>
                            <th>ผู้เปลี่ยนยาง</th>
                            <th>จำนวน <br>(เส้น)</th>
                            <th>ราคายางทั้งหมด <br>(บาท)</th>
                            <th>ราคาค่าแรงทั้งหมด <br>(บาท)</th>
                            <th>ค่าใช้จ่ายทั้งหมด <br>(บาท)</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-for="tire in tires">
                                <td>{{ tire.user.firstname }} {{ tire.user.lastname }}</td>
                                <td>{{ tire.truck.license_plate }}</td>
                                <td>{{ tire.tire.tire_date }}</td>
                                <td>{{ tire.tire.tire_employee }}</td>
                                <td>{{ tire.tire.quantity }}</td>
                                <td>{{ tire.tire.total_tire_price }}</td>
                                <td>{{ tire.tire.total_amout_cost - tire.tire.total_tire_price }}</td>
                                <td>{{ tire.tire.total_amout_cost }}</td>
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
						<canvas id="myChartTire" width="100%" height="100%"></canvas>								
					</div>
				</div>
			</div>
		</div>

		<div class="modal-content">
			<div class="section left-align">
                <div class="row">
                    <div class="input-field col s12 m9 l5 offset-l7 offset-m3">
                        <blockquote>
                            <p>ราคายางทั้งหมด: {{ totalTirePrice }} บาท</p>
                            <p>ราคาค่าแรงทั้งหมด: {{ totalWagePrice }} บาท</p>
                            <p>ค่าใช้จ่ายทั้งหมด: {{ totalAmoutCost }} บาท</p>
                        </blockquote>
                    </div>
                </div>						
            </div>
		</div>

		<div class="modal-footer">
            <a @click="onClickPrintReportTire" class="modal-action waves-effect waves-green btn-flat">Print</a>
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
                totalWagePrice: 0,
                totalTirePrice: 0,
				totalAmoutCost: 0,
			}
		},
        props: {
            tires: { require: true },
        },
        methods: {
            onClickPrintReportTire: function() {
                let printData = [];
				$.each(this.tires, function(index, tire) {
					const dataPush = {
						name: `${tire.user.firstname} ${tire.user.lastname}`,
						licensePlate: tire.truck.license_plate,
						tireDate: tire.tire.tire_date,
						tireEmployee: tire.tire.tire_employee,
						quantity: tire.tire.quantity,
						totalTirePrice: tire.tire.total_tire_price,
						totalWage: (tire.tire.total_amout_cost - tire.tire.total_tire_price),
						amountCost: tire.tire.total_amout_cost,
					}
					printData.push(dataPush);
				}); 
				printJS({
					printable: printData, 
					properties: [
						'name', 
						'licensePlate', 
						'tireDate', 
						'tireEmployee', 
						'quantity',
						'totalTirePrice', 
						'totalWage', 
						'amountCost', 
					],
					header: 'รายงานการซ่อมบำรุง',
					type: 'json'
				});
            },
            computeGraph: function() {
                let tirePrice = 0;
                let wagePrice = 0;
                let amoutCost = 0;

                $.each(this.tires, function(index, tire) {
                    tirePrice = tirePrice + tire.tire.total_tire_price
                    wagePrice = wagePrice + ( tire.tire.total_amout_cost - tire.tire.total_tire_price )
                    amoutCost = amoutCost + tire.tire.total_amout_cost
                }); 
                this.totalAmoutCost = amoutCost;
                this.totalWagePrice = wagePrice;
                this.totalTirePrice = tirePrice;
                
                ///////////////////////////
                let date = [];
                let tireGraph = [];
                let wageGraph = [];
                let amoutCostGraph = [];

                $.each(this.tires, function(index, tire) {
                    date.push(tire.tire.tire_date);
                    tireGraph.push(tire.tire.total_tire_price)
                    wageGraph.push(tire.tire.total_amout_cost - tire.tire.total_tire_price)
                    amoutCostGraph.push(tire.tire.total_amout_cost)
                }); 
                this.showGraph(date, tireGraph, wageGraph, amoutCostGraph);
            },
            showGraph: function(date, tireGraph, wageGraph, amoutCostGraph) {
				const ctxTire = document.getElementById("myChartTire");
				const myChartTire = new Chart(ctxTire, {
					type: 'line',
					data: {
						labels: date,
						datasets: [
                            {
								label: 'ราคายาง (บาท)',
								data: tireGraph,
								fill: false,
								borderColor: 'rgba(255,99,132,1)',
							},
                            {
								label: 'ราคาค่าแรง (บาท)',
								data: wageGraph,
								fill: false,
								borderColor: 'rgba(75,192,192,1)',
							},
							{
								label: 'ค่าใช้จ่ายทั้งหมด (บาท)',
								data: amoutCostGraph,
								fill: false,
								borderColor: 'rgba(54, 162, 235, 1)',
							},
						]
					},
				});
			}
        },
        watch: {
			tires: function () {
				this.computeGraph();
			}
		},
        mounted() {
            $('.materialboxed').materialbox();
            this.computeGraph();
		}
    }
</script>