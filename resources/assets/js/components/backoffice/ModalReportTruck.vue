<template>
    <div id="modalReportTruck" v-show="false" class="modal modal-fixed-footer left-align">
        <input type="hidden" name="_token" v-model="csrfToken">
        <input type="hidden" name="_method" value="PUT">
        <div id="pdfTruck" class="modal-content">
            <h4>ข้อมูลรถบรรทุก</h4>
            <div class="divider"></div>

            <div class="row mt25">
                <div class="col s12 m12 l12 center-align">
                    <img style="height: 150px; margin-left:35%; margin-bottom: 30px"  class="responsive-img" :src="photo" alt="photo">
                </div>
            </div>

            <div class="section">
                <h5>ข้อมูลทั่วไป</h5>
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <label>เลขทะเบียน</label>
                        <p>{{ truck.license_plate }}</p>
                    </div>
                    <div class="input-field col s12 m12 l6">
                        <label>เจ้าของ</label>
                        <p>{{ truck.owner }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <label>วันที่จดทะเบียน</label>
                        <p>{{ truck.register_date }}</p>
                    </div>
                    <div class="input-field col s12 m12 l6">
                        <label>วันที่ต่อทะเบียน</label>
                        <p>{{ truck.annual_tax_date }}</p>
                    </div>
                </div>
            </div>

            <div class="sectiton">
                <h5>ข้อมูลเชื้อเพลิง</h5>
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <label>น้ำมันเครื่องยนต์</label>
                        <p>{{ truck.gasoline }}</p>
                    </div>
                    <div class="input-field col s12 m12 l6">
                        <label>น้ำมันห้องเครื่อง</label>
                        <p>{{ truck.lubricator }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <label>น้ำมันห้องเกียร์</label>
                        <p>{{ truck.gear_box_oil }}</p>
                    </div>
                    <div class="input-field col s12 m12 l6">
                        <label>น้ำมันเฟืองท้าย</label>
                        <p>{{ truck.final_gear_oil }}</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h5>ข้อมูลล้อรถ</h5>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <label>จำนวนแถวของล้อ</label>
                        <p>{{ rowsOfWheel }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l4" v-for="(number, index) in numberWheelPerRow">
                        <label>จำนวนล้อ/แถว {{ index+1 }}</label>
                        <p>{{ number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import printJS from 'print-js';
    export default {
        props: {
            id: { require: true },
            photo: { require: true },
        },
        data() {
            return {
                truck: [],
				rowsOfWheel: '',
				numberWheelPerRow: '',
            }
        },
        beforeMount() {
            axios.get('/backoffice/truck/' + this.id + '/edit')
            .then((response) => {
                this.truck = response.data
                this.rowsOfWheel = this.truck.row_of_wheel
                this.numberWheelPerRow = JSON.parse(this.truck.number_wheel_per_row) 

                const printData = [{
                    licensePlate: this.truck.license_plate,
                    owner: this.truck.owner,
                    registerDate: this.truck.register_date,
                    annualTaxDate: this.truck.annual_tax_date,
                    gasoline: this.truck.gasoline,
                    lubricator: this.truck.lubricator,
                    gearBoxOil: this.truck.gear_box_oil,
                    finalGearOil: this.truck.final_gear_oil,
                    rowsOfWheel: this.rowsOfWheel,
                    numberWheelPerRow: JSON.stringify(this.numberWheelPerRow),
                }];

                printJS({
                    printable: printData, 
                    properties: [
                        'licensePlate',
                        'owner',
                        'registerDate',
                        'annualTaxDate',
                        'gasoline',
                        'lubricator',
                        'gearBoxOil',
                        'finalGearOil',
                        'rowsOfWheel',
                        'numberWheelPerRow',
                    ],
                    header: 'ข้อมูลรถบรรทุก',
                    type: 'json'
                });
                // Vue.nextTick(function () {
                //     printJS('pdfTruck', 'html');   
                // })
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        updated() { 
            $('#modalReportTruck').modal('close');
        },
    }
</script>