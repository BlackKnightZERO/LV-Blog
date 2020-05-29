<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20" v-if="!loadingSpinner">
					<p class="_title0">Roles 
                        <Select prefix="md-paper-plane" v-model="data.id"  placeholder="User type" style="width:300px; margin-left:5px;">
                            <Option :value="r.id" v-for="(r,i) in roles" :key="i">{{ r.roleName }}</Option>
                        </Select>
                    </p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>Resources</th>
								<th style="text-align:center">Add <Icon type="md-add" size="18"></Icon></th>
								<th style="text-align:center">Edit <Icon type="md-create" size="18"></Icon></th>
								<th style="text-align:center">Delete <Icon type="ios-remove" size="18"></Icon></th>
								<th style="text-align:center">View <Icon type="ios-eye" size="18"></Icon></th>
							</tr>							
							<tr v-for="(resource, index) in resources" :key="index">
								<td class="_table_name">
									{{ resource.resourceName }}
								</td>
                                <td style="text-align:center">
                                    <Checkbox v-model="resource.add">
                                    </Checkbox>    
                                </td>
                                <td style="text-align:center">
                                    <Checkbox v-model="resource.edit">
                                    </Checkbox>    
                                </td>
                                <td style="text-align:center">
                                    <Checkbox v-model="resource.delete">
                                    </Checkbox>    
                                </td>
                                <td style="text-align:center">
                                    <Checkbox v-model="resource.view">
                                    </Checkbox>    
                                </td>
							</tr>							
						</table>
                        <div style="margin-left:5px;">
                            <Button type="primary" @click="update" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
                        </div>
                    </div>
				</div>
				<div v-else>
					<div class="lds-ripple"><div></div><div></div></div>
				</div>                       	
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
export default {
	data(){
		return{
			data: {
                roleName:'',
                id: null,
			},
			isAdding: false,
            loadingSpinner: true, 
            roles:[],
            resources:[
                {resourceName:'Dashboard',add:false,edit:false,delete:false,view:false, name:'dashboard'},
                {resourceName:'Tags',add:false,edit:false,delete:false,view:false, name:'tags'},
                {resourceName:'Category',add:false,edit:false,delete:false,view:false, name:'category'},
                {resourceName:'Users',add:false,edit:false,delete:false,view:false, name:'users'},
                {resourceName:'Role',add:false,edit:false,delete:false,view:false, name:'role'},
                {resourceName:'Permission',add:false,edit:false,delete:false,view:false, name:'permission'},
                ],
		}	
	},
	async created(){
		const res = await this.callApi('get','/admin/role/get_roles');
		if(res.status==200){
            console.log(res);
            this.resources = JSON.parse(res.data[3].permission);
			this.loadingSpinner=false
			this.roles = res.data
		} else {
			this.loadingSpinner=false
			this.e();
		}
	},

	methods:{
		//update
		async update(){
			this.btnloading();
			if(!this.data.id){
				this.btnloadingOff();
				return this.e('Role Selection Required');
            } 
            let permission = JSON.stringify(this.resources);
			const res = await this.callApi('post','/admin/permission/update_permission',{id:this.data.id,permission:permission});
			if(res.status===200){
                
				// this.resources = JSON.parse(res.data.permission);
				this.btnloadingOff();
				this.s('Permission Updated Successfully!');
			} else if(res.status===422) {
				for(let i in res.data.errors) this.e(res.data.errors[i])
				this.btnloadingOff();
			} else {
				this.btnloadingOff();
                this.e();
            }
		},

		//others
		btnloading(){
			this.isAdding=true;
		},
		btnloadingOff(){
			this.isAdding=false;
        },
    }
}
</script>




			