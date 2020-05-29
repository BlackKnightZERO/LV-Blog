<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20" v-if="!loadingSpinner">
					<p class="_title0">Roles <Button @click="openAddModal"><Icon type="md-add" /> Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>ID</th>
								<th>Role</th>
								<!-- <th>Permissions</th> -->
								<th>Created At</th>
								<th>Action</th>
							</tr>							
							<tr v-for="(role, index) in roles" :key="index">
								<td>{{ index+1 }}</td>
								<td class="_table_name">
									<Badge status="success" v-if="role.isPermitted==1" />
									<Badge status="error" v-else />
									{{ role.roleName }}
								</td>
                                <!-- <td>{{ role.permission }}</td> -->
								<td>{{ role.created_at }}</td>
								<td>
									<Button type="info" size="small" @click="openEditModal(role, index)">Edit</Button>
									<Button type="error" size="small" @click="openDeleteModal(role, index)">Delete</Button>
								</td>
							</tr>							
						</table>
					</div>
				</div>
				<div v-else>
					<div class="lds-ripple"><div></div><div></div></div>
				</div>
				 <!-- <Page :total="100" /> -->
				 <!-- tag adding modal -->
				  	<Modal
						v-model="addModal"
						title="Add a Role"
						:mask-closable="false"
						:closable="true"
						>
                        <div class="spacer2">
						    <Input prefix="ios-body" clearable v-model="data.roleName" placeholder="Role Name" style="width: 100%" />
                        </div>
                         <br>
                        <!--<div class="spacer2">
                            <CheckboxGroup>
                                <span class="mr-10">Permissions</span>
                                <Checkbox label="Add" v-model="data.permissionAdd">
                                    <Icon type="md-add" size="18"></Icon>
                                    <span>Add</span>
                                </Checkbox>    
                                <Checkbox label="View" v-model="data.permissionView">
                                    <Icon type="ios-eye" size="18"></Icon>
                                    <span>View</span>
                                </Checkbox>  
                                <Checkbox label="Edit" v-model="data.permissionEdit">
                                    <Icon type="md-create" size="18"></Icon>
                                    <span>Edit</span>
                                </Checkbox>    
                                <Checkbox label="Delete" v-model="data.permissionDelete">
                                    <Icon type="ios-remove-circle" size="18"></Icon>
                                    <span>Delete</span>
                                </Checkbox>  
                            </CheckboxGroup>  
                        </div> -->
                        <div class="spacer2">
                            <span class="mr-10">Access Control</span>
                            <i-switch v-model="data.isPermitted" @on-change="!data.isPermitted" true-color="#13ce66" false-color="#ff4949" />
                        </div>     
                    	<div slot="footer">
							<Button type="default" @click="closeAddModal">close</Button>
							<Button type="primary" @click="add" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding..' : 'Add'}}</Button>
						</div>

					</Modal>	
				 <!-- tag adding modal -->	
				  <!-- tag edit modal -->
				  	<Modal
						v-model="editModal"
						title="Edit Role"
						:mask-closable="false"
						:closable="true"
						>
						<div class="spacer2">
							<Input prefix="ios-link" clearable v-model="editData.roleName" placeholder="Role Name" style="width: 100%" />
						</div>
						<br>
						<div class="spacer2">
						 	<i-switch v-model="editData.isPermitted" @on-change="!editData.isPermitted" true-color="#13ce66" false-color="#ff4949" />
						</div>
						<div slot="footer">
							<Button type="default" @click="closeEditModal">close</Button>
							<Button type="primary" @click="update" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
						</div>

					</Modal>	
				 <!-- tag edit modal -->	
				 <deleteModal></deleteModal>
			</div>
		</div>
	</div>
</template>
<style scoped>
    .spacer2{
        margin-top:1%;
    }
    .mr-10{
        margin-right:10px;
        font-weight:bold;
    }
</style>
<script>
import deleteModal from '../components/deletemodal';
import { mapGetters } from 'vuex';
export default {
	components:{
		deleteModal,
	},
	data(){
		return{
			data: {
                roleName:'',
                isPermitted: false,
                // permissionAdd: false,
                // permissionView: false,
                // permissionEdit: false,
                // permissionDelete: false,
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			roles:[],
			editData: {
				id:'',
				roleName:'',
				isPermitted:null,
			},
			editIndex:-1,
            loadingSpinner: true, 
		}	
	},

	computed:{
        ...mapGetters(['getDeleteModalObj'])
	},
	watch:{
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.roles.splice(obj.deleteIndex,1);
			}
		}
	},

	async created(){
		const res = await this.callApi('get','/admin/role/get_roles');
		// console.log(JSON.parse(res.data[0].permission));

		if(res.status==200){
			this.loadingSpinner=false
			this.roles = res.data
			// this.roles.permission = JSON.parse(res.data.permission)
		} else {
			this.loadingSpinner=false
			this.e();
		}
	},

	methods:{

		//add
		async add(){
			this.btnloading();
			if(this.data.roleName.trim()==''){
				this.btnloadingOff();
				return this.e('RoleName is required!');
			} 
			const res = await this.callApi('post','/admin/role/create_role',this.data);
			if(res.status===201){
				this.roles.unshift(res.data);
				this.btnloadingOff();
				this.s('Role has been added successfully!');
				this.closeAddModal();
				this.clearAddModalTextField();
			} else if(res.status===422) {
				for(let i in res.data.errors) this.e(res.data.errors[i])
				this.btnloadingOff();
			} else {
				this.btnloadingOff();
                this.e();
            }
			
		},
		//update
		async update(){
			this.btnloading();
			if(this.editData.roleName.trim()==''){
				this.btnloadingOff();
				return this.e('Role Name is required!');
			} 
			const res = await this.callApi('post','/admin/role/update_role',this.editData);
			if(res.status===200){
				this.roles[this.editIndex].roleName = this.editData.roleName;
				this.roles[this.editIndex].isPermitted = this.editData.isPermitted;
				this.btnloadingOff();
				this.s('Role has been edited successfully!');
				this.closeEditModal();
				this.clearEditModalTextField();
			} else if(res.status===422) {
				for(let i in res.data.errors) this.e(res.data.errors[i])
				this.btnloadingOff();
			} else {
				this.btnloadingOff();
                this.e();
            }
		},

		//others
		openAddModal(){
			this.addModal = true;
		},
		closeAddModal(){
			this.addModal = false;
		},
		btnloading(){
			this.isAdding=true;
		},
		btnloadingOff(){
			this.isAdding=false;
		},
		clearAddModalTextField(){
			this.data.roleName=''
			this.data.isPermitted=false
		},
		clearEditModalTextField(){
			this.editData.roleName=''
			this.editData.isPermitted=null
		},
		openEditModal(role, index){	
			this.editData.id = role.id;
			this.editData.roleName = role.roleName;
			this.editData.isPermitted = (role.isPermitted==1) ? true : false;
			this.editIndex = index;
			this.editModal=true;
		},
		closeEditModal(){
			this.editData.id = '';
			this.editData.roleName = '';
			this.editData.isPermitted=null
			this.editModal = false;
			this.editIndex = -1;
		},
		openDeleteModal(role, index){
			const deleteModalObj = {
									showDeleteModal:true,
									deleteUrl:'/admin/role/delete_role',
									deleteIndex:index,
									isDeleted:false,
									deleteData:role,
			}
			this.$store.commit('setDeleteModalObj', deleteModalObj);
		},
	},
	cancel(){
			if(this.addModal==true){
				this.clearAddModalTextField();
				this.closeAddModal();
			} else if (this.editModal==true){
				this.clearEditModalTextField();
				this.closeEditModal();
			} else {

			}
    },
    change() {
        this.$Message.info('开关状态：' + this.switch1);
    }
}
</script>




			