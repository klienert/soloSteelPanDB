<?php
require_once 'image_fileconstants.php';

/**
 * Purpose:         Validates an uploaded profile image file
 *
 * Description:     Validates an uploaded profile image file is not greater than SL_MAX_FIE (1/2 MB),
 *                  and is either a jpg or png image type, and has no errors. If the image file
 *                  validates to these constraints, an error message containing an empty string is
 *                  returned. If there is an error, a string containing constraints the file failed
 *                  to validate to are returned.
 *
 * @return string   Empty if validation is successful, otherwise error string containing
 *                  constraints the image file failed to validate to.
 */
function validateProfileImageFile()
{
    $error_message = "";

    // Check for $_FILES being set and no errors.
    if (isset($_FILES) && $_FILES['profile_image_file']['error'] == UPLOAD_ERR_OK)
    {
        // Check for uploaded file < Max file size AND an acceptable image type
        if ($_FILES['profile_image_file']['size'] > PAN_MAX_FILE_SIZE)
        {
            $error_message = "The profile file image must be less than " . PAN_MAX_FILE_SIZE . " Bytes";
        }

        // check for same name as EX_DEFAULT_PROFILE_FILE_NAME
        if ($_FILES['profile_image_file']['name'] == PAN_DEFAULT_PROFILE_FILE_NAME) 
        {            
            $error_message = "Please choose a different file name for your profile file image.";
        }

        // use in_array()        
        $file_type = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif');
        
        $image_type = $_FILES['profile_image_file']['type'];
        
        if (!in_array($image_type, $file_type) )
        {
            if (empty($error_message))
            {
                $error_message = "The profile file image must be of type jpg/jpeg, png, or gif.";
            }
            else
            {
                $error_message = ", and be an image of type jpg, png, or gif.";
            }
        }
    }
    elseif (isset($_FILES) && $_FILES['profile_image_file']['error'] != UPLOAD_ERR_NO_FILE
        && $_FILES['profile_image_file']['error'] != UPLOAD_ERR_OK)
    {
        $error_message = "Error uploading profile image file.";
    }

    return $error_message;
}

/**
 * Purpose:         Moves an uploaded profile image file to the PAN_UPLOAD_PATH (images/) folder and
 *                  return the path location.
 *
 * Description:     Moves an uploaded profile image file from the temporary server location to the
 *                  PAN_UPLOAD_PATH (images/) folder IF a profile image file was uploaded and returns
 *                  the path location of the uploaded file by appending the file name to the
 *                  PAN_UPLOAD_PATH (e.g. images/profile_image.png). IF a profile image file was NOT
 *                  uploaded, an empty string will be returned for the path.
 *
 * @return string   Path to profile image file IF a file was uploaded AND moved to the PAN_UPLOAD_PATH
 *                  (images/) folder, otherwise and empty string.
 */
function addProfileImageFileReturnPathLocation()
{
    $profile_file_path = "";

    // Check for $_FILES being set and no errors.
    if (isset($_FILES) && $_FILES['profile_image_file']['error'] == UPLOAD_ERR_OK)
    {
        $profile_file_path = PAN_UPLOAD_PATH . $_FILES['profile_image_file']['name'];

        if (!move_uploaded_file($_FILES['profile_image_file']['tmp_name'], $profile_file_path))
        {
            $profile_file_path = "";
        }
    }    
    return $profile_file_path;
}

/**
 * Purpose:         Removes a file given a path to that file.
 *
 * Description:     Removes the file referenced by $profile_file_path. Supresses error
 *                  if file cannot be removed.
 *
 * @param $profile_file_path
 */
function removeProfileImageFile($profile_file_path)
{
    @unlink($profile_file_path);
}