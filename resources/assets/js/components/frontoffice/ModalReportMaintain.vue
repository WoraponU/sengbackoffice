<template>
    <div id="reportMaintain" class="modal bottom-sheet">
		<div id="pdfMaintain" class="modal-content">
			<h4>รายงานการซ่อมบำรุง</h4>
            <div class="row">
                <div class="col s12 m12 l12">
                    <table class="centered">
                        <thead>
                        <tr>
                            <th>คนขับรถ</th>
                            <th>ทะเบียนรถ</th>
                            <th>วันที่</th>
                            <th>ผู้ซ่อม</th>
                            <th>ค่าแรงทั้งหมด</th>
                            <th>ค่าอะไหล่ทั้งหมด</th>
                            <th>ค่าใช้จ่ายทั้งหมด</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-for="maintain in maintains">
                                <td>{{ maintain.user.firstname }} {{ maintain.user.lastname }}</td>
                                <td>{{ maintain.truck.license_plate }}</td>
                                <td>{{ maintain.maintain.maintain_date }}</td>
                                <td>{{ maintain.maintain.maintain_employee }}</td>
                                <td>{{ maintain.maintain.total_wage }}</td>
                                <td>{{ maintain.maintain.total_spare }}</td>
                                <td>{{ maintain.maintain.amount_cost }}</td>
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
						<canvas id="myChartMaintain" width="100%" height="100%"></canvas>								
					</div>
				</div>
			</div>
		</div>

		<div class="modal-content">
			<div class="section left-align">
                <div class="row">
                    <div class="input-field col s12 m9 l5 offset-l7 offset-m3">
                        <blockquote>
                            <p>ค่าแรงทั้งหมด: {{ totalWage }} บาท</p>
                            <p>ค่าอะไหล่ทั้งหมด: {{ totalSpare  }} บาท</p>
                            <p>ค่าใช้จ่ายทั้งหมด: {{ amountCost }} บาท</p>
                        </blockquote>
                    </div>
                </div>						
            </div>
		</div>

		<div class="modal-footer">
            <a @click="onClickPrintReportMaintain" class="modal-action waves-effect waves-green btn-flat">Print</a>
			<a type="button" class="modal-action modal-close waves-effect waves-green btn-flat">ปิด</a>
		</div>
	</div>
</template>

<script>
    import printJS from 'print-js';

    export default {
        data() {
			return {
				totalWage: 0,
                totalSpare: 0,
                amountCost: 0,
			}
		},
        props: {
            maintains: { require: true },
        },
        methods: {
            onClickPrintReportMaintain: function() {
				let printData = [];
				$.each(this.maintains, function(index, maintain) {
					const dataPush = {
						name: `${maintain.user.firstname} ${maintain.user.lastname}`,
						licensePlate: maintain.truck.license_plate,
						maintainDate: maintain.maintain.maintain_date,
						maintainEmployee: maintain.maintain.maintain_employee,
						totalWage: maintain.maintain.total_wage,
						totalSpare: maintain.maintain.total_spare,
						amountCost: maintain.maintain.amount_cost,
					}
					printData.push(dataPush);
				}); 
				printJS({
					printable: printData, 
					properties: [
						'name', 
						'licensePlate', 
						'maintainDate', 
						'maintainEmployee', 
						'totalWage',
						'totalSpare', 
						'amountCost', 
					],
					header: 'รายงานการซ่อมบำรุง',
					type: 'json'
				});
            },
			computeGraph: function() {
				let wage = 0;
				let spare = 0;
				let cost = 0;
				$.each(this.maintains, function(index, maintain) {
					wage = wage + maintain.maintain.total_wage
					spare = spare + maintain.maintain.total_spare
					cost = cost + maintain.maintain.amount_cost
				}); 
				this.totalWage = wage.toFixed(2);
				this.totalSpare = spare.toFixed(2);
				this.amountCost = cost.toFixed(2);

				///////////////////////
				let date = [];
				let wageGraph = [];
				let spareGraph = [];
				let costGraph = [];

				$.each(this.maintains, function(index, maintain) {
					date.push(maintain.maintain.maintain_date);

					wageGraph.push(maintain.maintain.total_wage)
					spareGraph.push(maintain.maintain.total_spare)
					costGraph.push(maintain.maintain.amount_cost)
				}); 
				this.showGraph(date, wageGraph, spareGraph, costGraph);
			},
            showGraph: function(date, wageGraph, spareGraph, costGraph) {
				const ctxMaintain = document.getElementById("myChartMaintain");
				const myChartMaintain = new Chart(ctxMaintain, {
					type: 'line',
					data: {
						labels: date,
						datasets: [
							{
								label: 'ค่าแรง (บาท)',
								data: wageGraph,
								fill: false,
								borderColor: 'rgba(255,99,132,1)',
							},
							{
								label: 'ค่าอะไหล่ (บาท)',
								data: spareGraph,
								fill: false,							
								borderColor: 'rgba(75,192,192,1)',
							},
							{
								label: 'ค่าใช้จ่าย (บาท)',
								data: costGraph,
								fill: false,							
								borderColor: 'rgba(54, 162, 235, 1)',
							},
						]
					},
				});
			}
        },
		watch: {
			maintains: function () {
				this.computeGraph();
			}
		},
        mounted() {
            $('.materialboxed').materialbox();
			this.computeGraph();
		}
    }
</script>