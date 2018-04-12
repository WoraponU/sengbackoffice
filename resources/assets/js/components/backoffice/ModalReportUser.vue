<template>
    <div id="modalReportUser" v-show="false" class="modal modal-fixed-footer left-align">
        <input type="hidden" name="_token" v-model="csrfToken">
        <input type="hidden" name="_method" value="PUT">
        <div id="pdfUser" class="modal-content">
            <h4>ข้อมูลพนักงาน</h4>
            <div class="divider"></div>

            <div class="row mt25">
                <div class="col s12 m12 l12 center-align">
                    <img style="height: 150px; margin-left:35%; margin-bottom: 30px" class="circle responsive-img" :src="photo" alt="photo">
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <label>ชื่อจริง</label>
                    <p>{{ user.firstname }}</p>
                </div>
                <div class="col s12 m12 l6">
                    <label>นามสกุล</label>
                    <p>{{ user.lastname }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <label>เลขประจำตัวประชาชน</label>
                    <p>{{ identificationNumber }}</p>
                </div>
                <div class="col s12 m12 l6">
                    <label for="driverLicense">เลขใบขับขี่</label>
                    <p>{{ driverLicense }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <label>เบอร์</label>
                    <p>{{ phone }}</p>
                </div>
                <div class="col s12 m12 l6">
                    <label>อีเมลล์</label>
                    <p>{{ user.email }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12">
                    <label>ที่อยู่</label>
                    <p>{{ user.address }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <label class="role-label">ตำแหน่ง</label>
                    <p>{{ selected }}</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-close modal-action waves-effect waves-green btn-flat">ปิด</button>
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
                user: [],
                identificationNumber: '',
                driverLicense: '',
                phone: '',
                selected: '',
            }
        },
        beforeMount() {            
            axios.get('/backoffice/user/' + this.id + '/edit')
            .then((response) => {
                this.user = response.data
                this.identificationNumber = this.user.identification_number
                this.driverLicense = this.user.driver_license
                this.phone = '0' + this.user.phone;

                this.selected = this.user.role

                if (this.selected == 'main_admin') {
                    this.selected = 'ผู้ดูแลหลัก';
                } else if (this.selected == 'admin') { 
                    this.selected =  'ผู้เพิ่มข้อมูล';
                } else {
                    this.selected =  'พนักงานขับรถ';
                }

                const printData = [{
                    name: `${this.user.firstname} ${this.user.lastname}`,
                    licensePlate: this.identificationNumber,
                    driverLicense: this.driverLicense,
                    phone: this.phone,
                    email: this.user.email,
                    address: this.user.address,
                    role: this.selected,
                }];

                printJS({
                    printable: printData, 
                    properties: [
                        'name', 
                        'licensePlate', 

                        'driverLicense', 
                        'phone', 
                        'email',
                        'address', 
                        'role', 
                    ],
                    header: 'ข้อมูลพนักงาน',
                    type: 'json'
                });
                // Vue.nextTick(function () {
                    // printJS('pdfUser', 'html'); 
                // })    
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        updated() {  
            $('#modalReportUser').modal('close');
        },
    }
</script>