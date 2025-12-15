<?php
?>

<script src="https://unpkg.com/@tensorflow/tfjs"></script>
<script src="https://unpkg.com/nsfwjs"></script>

<input type="file" id="imageInput" accept="image/*" />

<script>

    let model = null;

    window.onload = async () => {
        model = await nsfwjs.load("./assets/nsfwjs/models/mobilenet_v2/");
        console.log("Model is ready !!!");
    }

    async function isNormalImage(e, isFileLink = false) {
        const file = isFileLink ? e : e.target.files[0];

        if (!file) {
            console.log("No file detected");
            return;
        }

        const img = new Image();
        img.src = URL.createObjectURL(file);
        await img.decode();

        const predictions = await model.classify(img);

        console.log(predictions);

        // pick the highest score
        const top = predictions[0];

        if (top.className !== "Neutral" && top.probability > 0.7) {
            console.log("This image looks NSFW. Upload not allowed.");
            if (isFileLink === false) e.target.value = "";
            return false;
        }

        return img.src;
    }
</script>