function setImgPreview(e)
{
    const fr = new FileReader();
    const previewElm = document.getElementById(e.target.dataset.targetId);

    fr.readAsDataURL(e.target.files[0]);

    fr.onload = function () {
        if (previewElm.firstChild) {
            // 子要素がある場合は全削除
            while (previewElm.firstChild) {
                previewElm.removeChild(previewElm.firstChild);
            }
        }

        const imgElm = document.createElement('img');
        imgElm.src = this.result;

        // classが与えられているか確認して、与えられている場合は
        // クラスを付与
        const classes = e.target.dataset.classes;
        if (classes) {
            const classArray = classes.split(' ');
            for (let i = 0; i < classArray.length; i++) {
                imgElm.classList.add(classArray[i]);
            }
        }

        // imgタグを挿入
        previewElm.appendChild(imgElm);
    }
}