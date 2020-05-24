<template>
    <div>
        <!-- delete modal -->
					<Modal :value="getDeleteModalObj.showDeleteModal" 
                    :mask-closeable="false"
                    :closeable="false"
                    width="360">
						<p slot="header" style="color:#f60;text-align:center">
							<Icon type="ios-information-circle"></Icon>
							<span>Delete confirmation</span>
						</p>
						<div style="text-align:center">
							<p>Are you sure you want to delete it?</p>
						</div>
						<div slot="footer">
							<Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCategory">Delete</Button>
						</div>
					</Modal>
		<!-- delete modal -->
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    data(){
      return{
          isDeleting:false,
      }  
    },
    computed:{
        ...mapGetters(['getDeleteModalObj'])
    },
    methods:{
        async deleteCategory(){
            this.isDeleting = true;
            if(this.getDeleteModalObj.deleteData.iconImage){
                this.clearOldIconImage();
            }
            const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.deleteData);
            if(res.status===200){
                this.isDeleting = false;
                this.s('Category has been deleted successfully!');
                this.$store.commit('setDeleteModal', true);
            } else {
                this.isDeleting = false;
                this.e();
                this.$store.commit('setDeleteModal', false);
            }
			
        },
        async clearOldIconImage(){
            let image = this.getDeleteModalObj.deleteData.iconImage;
            const res = await this.callApi('post','/admin/category/remove_image',{"iconImage": image});
			if(res.status===200){
				console.log('successfully deleted category pic from server');
			} else {
				console.log('operation failed: old category pic failed to delete from server');
			}
        },
    }
}
</script>